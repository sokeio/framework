<?php

namespace BytePlatform\Concerns;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use BytePlatform\Laravel\JsonData;

trait WithGeneratorStub
{
    public $force = false;
    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Get the laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the laravel filesystem instance.
     *
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    protected $ConfigStubName = 'byte';
    protected $GeneratorConfig;
    private $_base_name;
    private $_system_base;
    private $_files;
    private $_data_info;
    private $_path_stub;
    private $_namespace;
    private $_template_files;
    public function resetCache()
    {
        $this->_namespace = '';
        $this->_data_info = '';
    }
    public function getValueConfig($key, $default = '')
    {
        return JsonData::getValueByKey($this->GeneratorConfig, $key, $default);
    }
    public function bootWithGeneratorStub(
        Filesystem $filesystem = null
    ) {
        $this->filesystem = $filesystem ?? $this->laravel['files'];
        $this->GeneratorConfig = config($this->ConfigStubName);
    }
    public function getBaseTypeName()
    {
        return $this->option('type');
    }
    public function getFileName()
    {
        return $this->argument('name');
    }
    public function getSystemBase()
    {
        return $this->_system_base ?? ($this->_system_base = platform_by($this->getBaseTypeName()));
    }
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getBaseName()
    {
        if (!$this->_base_name)
            $this->_base_name = Str::studly($this->option('base'));
        if (!$this->_base_name)
            $this->_base_name = $this->getSystemBase()->getUsed();
        return $this->_base_name;
    }
    public function getDataInfo()
    {
        return $this->_data_info ?? ($this->_data_info = $this->getSystemBase()->find($this->getBaseName()));
    }
    public function getFiles()
    {
        return $this->_files ?? ($this->_files = array_merge(
            $this->getValueConfig('stubs.files.common', []),
            $this->getValueConfig('stubs.files.' . $this->getBaseTypeName(), [])
        ));
    }
    public function getStub()
    {
        return $this->_path_stub ?? ($this->_path_stub = $this->getValueConfig('stubs.path', ''));
    }
    public function getFolders()
    {
        return $this->_folders ?? ($this->_folders = $this->getValueConfig('paths', []));
    }
    public function getTemplates()
    {
        return $this->_template_files ?? ($this->_template_files = $this->getValueConfig('stubs.templates', []));
    }
    protected function getReplacements($keys)
    {
        $replaces = [];
        if (!in_array('BASE_TYPE', $keys)) {
            $keys[] = 'BASE_TYPE';
        }
        foreach ($keys as $key) {
            if (method_exists($this, $method = 'get' . ucfirst(Str::studly(strtolower($key))) . 'Replacement')) {
                $replaces[$key] = $this->$method();
            } else {
                $replaces[$key] = null;
            }
        }

        return $replaces;
    }
    public function getContentWithReplace($content, $replacements, $doblue = false)
    {
        if ($doblue) {
            foreach ($replacements as $search => $replace) {
                $content = str_replace('$' . strtoupper($search) . '$', str_replace('\\', '\\\\',  $replace), $content);
            }
        } else {
            foreach ($replacements as $search => $replace) {
                $content = str_replace('$' . strtoupper($search) . '$', $replace, $content);
            }
        }

        return $content;
    }

    public function getPathStub($path)
    {
        $path_stub = $this->getStub() . '/' . $path;
        return file_exists($path_stub) ? $path_stub : __DIR__ . '/../../stubs/' .  $path;
    }
    public function getContentWithStub($stub)
    {
        $path_stub = $this->getPathStub($stub);
        return file_exists($path_stub) ? file_get_contents($path_stub) : '';
    }
    public function saveContentToFile($content, $path)
    {
        return file_put_contents($path, $content);
    }
    public function getPath($_path)
    {
        return $this->getSystemBase()->getPath($this->getStudlyNameReplacement()) . ($_path ? ('/' . $_path) : '');
    }
    public function generate($name = null)
    {
        $this->resetCache();
        $this->_base_name = Str::studly($name);
        if ($this->getSystemBase()->has($this->_base_name)) {
            if ($this->force) {
                $this->getSystemBase()->delete($this->_base_name);
            } else {
                $this->console->error("{$this->getBaseType()} [{ $this->_base_name}] already exist!");
                return E_ERROR;
            }
        }
        $this->GeneratorFolder();
        $this->GeneratorFile();
        return 0;
    }
    public function ProcessConvertClass($class)
    {
        $class = str_replace('/', '\\', $class);
        $pars = explode('\\', $class);
        $len = count($pars) - 1;
        $NAMESPACE = '';
        for ($i = 0; $i < $len; $i++) {
            if ($i == 0) {
                $NAMESPACE = Str::studly($pars[$i]);
            } else
                $NAMESPACE =   $NAMESPACE . '\\' . Str::studly($pars[$i]);
        }

        $CLASS = Str::studly($pars[$len]);
        return ['CLASS' => $CLASS, 'NAMESPACE' => $NAMESPACE];
    }
    public function GeneratorFileByStub($stub, $name = '')
    {

        $template = $this->getTemplates()[$stub];
        // return true;
        if (isset($template) && count($template) > 0) {
            $path = $this->getFolders()[isset($template['path']) ? $template['path'] : 'base'];
            $name_file = $name != '' ? $name : $template['name'];
            $replacements = isset($template['replacements']) ? $this->getReplacements($template['replacements']) : [];
            if (isset($replacements['NAMESPACE']) && isset($path['namespace']) && $path['namespace'] != '') {
                $replacements['NAMESPACE'] = $replacements['NAMESPACE'] . '\\' . $path['namespace'];
            }

            if (isset($replacements['NAMESPACE']) &&  (isset($replacements['CLASS']))) {
                if (isset($template['file_prex']) && $template['file_prex'] != '' && Str::contains(strtolower($replacements['CLASS']), strtolower($template['file_prex'])) === false) {
                    $replacements['CLASS'] .= $template['file_prex'];
                }

                $rs = $this->ProcessConvertClass($replacements['CLASS']);
                $replacements['CLASS'] = $rs['CLASS'];
                $replacements['CLASS_FILE'] = $replacements['CLASS'];
                $replacements['LOWER_CLASS_FILE'] = Str::lower($replacements['CLASS']);
                if ($rs['NAMESPACE']) {
                    $replacements['NAMESPACE'] = $replacements['NAMESPACE'] . '\\' . $rs['NAMESPACE'];
                    $replacements['CLASS_FILE'] = $rs['NAMESPACE'] . '\\' . $rs['CLASS'];
                }
            }
            $name_file = $this->getContentWithReplace($name_file, $replacements);

            $dataInfo = $this->getDataInfo();
            if ($dataInfo)
                $path = $dataInfo->getPath($path['path']) . '/' . str_replace('\\', '/', $name_file);
            else
                $path = $this->getPath($path['path']) . '/' . str_replace('\\', '/', $name_file);
            if (isset($template['stub']) && $template['stub']) {
                $content = $this->getContentWithStub($template['stub'] . '.stub');
            } else {
                $content = $this->getContentWithStub($stub . '.stub');
            }
            $content = $this->getContentWithReplace($content, $replacements, isset($template['doblue']) && $template['doblue']);
            if (isset($this->components) && $this->components)
                $this->components->info($path);
            if (!$this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }
            $this->saveContentToFile($content, $path);
        }
    }
    public function GeneratorFile()
    {
        foreach ($this->getFiles() as $stub) {
            $this->GeneratorFileByStub($stub);
        }
    }
    public function GeneratorFolder()
    {
        foreach ($this->getFolders() as $folder) {
            $only = JsonData::getValueByKey($folder, 'only', []);
            if ((!JsonData::getValueByKey($folder, 'generate', false) || (count($only) > 0 && !in_array($this->getBaseTypeName(), $only)))) {
                continue;
            }
            $path =  $this->getPath(JsonData::getValueByKey($folder, 'path', ''));
            if (isset($this->components) && $this->components)
                $this->components->info($path);
            $this->filesystem->makeDirectory($path, 0775, true);
            if ($this->getValueConfig('stubs.gitkeep', false)) {
                $this->generateGitKeep($path);
            }
        }
    }
    public function generateGitKeep($path)
    {
        $this->filesystem->put($path . '/.gitkeep', '');
    }
    public function getNamespaceByPath($pathName)
    {
        $path = $this->getFolders()[$pathName];
        if (isset($path['namespace']) && $path['namespace'] != '') {
            return $this->getNamespaceReplacement() . '\\' . $path['namespace'];
        }
        return $this->getNamespaceReplacement();
    }
    //------------------BEGIN: Replacement------------------------------
    /**
     * Get replacement for $LOWER_NAME$.
     *
     * @return string
     */
    public function getLowerNameReplacement()
    {
        return Str::lower($this->getBaseName());
    }
    /**
     * Get replacement for $STUDLY_NAME$.
     *
     * @return string
     */
    public function getStudlyNameReplacement()
    {
        return Str::studly($this->getBaseName());
    }
    /**
     * Get replacement for $NAMESPACE$.
     *
     * @return string
     */
    public function getNamespaceReplacement()
    {
        if (!$this->_namespace) {
            $dataInfo = $this->getDataInfo();
            if ($dataInfo) {
                $this->_namespace =  $dataInfo->getValue('namespace');
            } else {
                $this->_namespace = config('byte.namespace.root', config('byte.appdir.root', 'byteplatform')) . config('byte.namespace.' . $this->getBaseTypeName(), config('byte.appdir.' . $this->getBaseTypeName())) . "\\" . $this->getStudlyNameReplacement();
            }
        }
        return $this->_namespace;
    }
    /**
     * Get replacement for $BASE_TYPE_LOWER_NAME$.
     *
     * @return string
     */
    public function getBaseTypeLowerNameReplacement()
    {
        return Str::lower($this->getBaseTypeName());
    }
    /**
     * Get replacement for $BASE_TYPE$.
     *
     * @return string
     */
    public function getBaseTypeReplacement()
    {
        return Str::studly($this->getBaseTypeName());
    }
    /**
     * Get replacement for $VENDOR$.
     *
     * @return string
     */
    protected function getVendorReplacement()
    {
        return config('byte.composer.vendor');
    }
    /**
     * Get replacement for $AUTHOR_NAME$.
     *
     * @return string
     */
    protected function getAuthorNameReplacement()
    {
        return config('byte.composer.author.name');
    }
    /**
     * Get replacement for $AUTHOR_EMAIL$.
     *
     * @return string
     */
    protected function getAuthorEmailReplacement()
    {
        return config('byte.composer.author.email');
    }
    /**
     * Get replacement for $CLASS$.
     *
     * @return string
     */
    protected function getClassReplacement()
    {
        return Str::studly($this->getFileName());
    }
    /**
     * Get replacement for $LOWER_CLASS$.
     *
     * @return string
     */
    protected function getLowerClassReplacement()
    {
        return Str::lower($this->getFileName());
    }
    /**
     * Get replacement for $QUOTE$.
     *
     * @return string
     */
    protected function getQuoteReplacement()
    {
        return Inspiring::quotes()->random();
    }
    /**
     * Get replacement for $JSON_ID$.
     *
     * @return string
     */
    protected function getJsonIdReplacement()
    {
        return Str::uuid() . rand(10000, 10000000);
    }
    /**
     * Get replacement for $DESCRIPTION$.
     *
     * @return string
     */
    protected function getDescriptionReplacement()
    {
        return '';
    }
    /**
     * Get replacement for $FILE_MIGRATION$.
     *
     * @return string
     */
    protected function getFileMigrationReplacement()
    {
        return Carbon::now()->format('Y_m_d_His') . '_' . Str::lower($this->getFileName());
    }
    //------------------END  : Replacement------------------------------
}
