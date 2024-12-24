<?php

namespace Sokeio\Support\Platform;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ItemGenerate
{
    protected $configStubName = 'sokeio-stubs';
    protected $generatorConfig;
    protected $filesystem;
    protected $files;
    protected $pathStub;
    protected $folders;
    protected $templateFiles;
    protected $baseName;
    protected $fileName;
    protected $namespace;
    public function __construct(
        protected ItemManager $itemManager
    ) {
        $this->generatorConfig = config($this->configStubName);

        $this->filesystem = app('files');
    }
    public function getBaseType()
    {
        return $this->itemManager->getItemType();
    }
    public function getValueConfig($key, $default = '')
    {
        return data_get($this->generatorConfig, $key, $default);
    }
    public function getFiles()
    {
        return $this->files ?? ($this->files = array_merge(
            $this->getValueConfig('files.common', []),
            $this->getValueConfig('files.' . $this->getBaseType(), [])
        ));
    }
    public function getStub()
    {
        return $this->pathStub ?? ($this->pathStub = $this->getValueConfig('path', ''));
    }
    public function getFolders()
    {
        return $this->folders ?? ($this->folders = $this->getValueConfig('paths', []));
    }
    public function getTemplates()
    {
        return $this->templateFiles ?? ($this->templateFiles = $this->getValueConfig('templates', []));
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
        return $this->itemManager->getPath($this->getStudlyNameReplacement()) . ($_path ? ('/' . $_path) : '');
    }
    public function getBaseName()
    {
        return $this->baseName;
    }
    public function generate($name = null, $force = false)
    {
        $this->baseName = Str::studly($name);
        if ($this->itemManager->hasByName($this->baseName)) {
            if ($force) {
                $this->itemManager->deleteByName($this->baseName);
            } else {
                return E_ERROR;
            }
        }
        $this->generatorFolder();
        $this->generatorFile();
        return 0;
    }
    public function generateFile($baseName, $fileName, $type)
    {
        $this->baseName = $baseName;
        $this->fileName = $fileName;
        $this->generatorFileByStub($type);
    }
    public function processConvertClass($class)
    {
        $class = str_replace('/', '\\', $class);
        $pars = explode('\\', $class);
        $len = count($pars) - 1;
        $namespace = '';
        for ($i = 0; $i < $len; $i++) {
            if ($i == 0) {
                $namespace = Str::studly($pars[$i]);
            } else {
                $namespace =   $namespace . '\\' . Str::studly($pars[$i]);
            }
        }

        $class = Str::studly($pars[$len]);
        return ['CLASS' => $class, 'NAMESPACE' => $namespace];
    }
    public function getDataInfo()
    {
        return $this->itemManager->findByName($this->baseName);
    }
    public function getFileName()
    {
        return $this->fileName;
    }
    public function generatorFileByStub($stub, $name = '')
    {

        $template = $this->getTemplates()[$stub];
        if (isset($template) && empty($template) === false) {
            $path = $this->getFolders()[isset($template['path']) ? $template['path'] : 'base'];
            $name_file = $name != '' ? $name : $template['name'];
            $replacements = isset($template['replacements']) ? $this->getReplacements($template['replacements']) : [];
            if (isset($replacements['NAMESPACE']) && isset($path['namespace']) && $path['namespace'] != '') {
                $replacements['NAMESPACE'] = $replacements['NAMESPACE'] . '\\' . $path['namespace'];
            }

            if (isset($replacements['NAMESPACE']) &&  (isset($replacements['CLASS']))) {
                if (
                    isset($template['file_prex']) && $template['file_prex'] !== ''
                    &&
                    Str::contains(strtolower($replacements['CLASS']), strtolower($template['file_prex'])) === false
                ) {
                    $replacements['CLASS'] .= $template['file_prex'];
                }

                $rs = $this->processConvertClass($replacements['CLASS']);
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
            if ($dataInfo) {
                $path = $dataInfo->getPath($path['path']) . '/' . str_replace('\\', '/', $name_file);
            } else {
                $path = $this->getPath($path['path']) . '/' . str_replace('\\', '/', $name_file);
            }
            if (isset($template['stub']) && $template['stub']) {
                $content = $this->getContentWithStub($template['stub'] . '.stub');
            } else {
                $content = $this->getContentWithStub($stub . '.stub');
            }
            $doblue = isset($template['doblue']) && $template['doblue'];
            $content = $this->getContentWithReplace($content, $replacements,  $doblue);

            if (!$this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }
            $this->saveContentToFile($content, $path);
        }
    }
    public function generatorFile()
    {
        foreach ($this->getFiles() as $stub) {
            $this->generatorFileByStub($stub);
        }
    }
    public function generatorFolder()
    {
        foreach ($this->getFolders() as $folder) {
            $only = data_get($folder, 'only', []);
            $checkOnly = !empty($only) && !in_array($this->getBaseType(), $only);
            if (!data_get($folder, 'generate', false) || $checkOnly) {
                continue;
            }
            $path =  $this->getPath(data_get($folder, 'path', ''));

            $this->filesystem->makeDirectory($path, 0775, true);
            if ($this->getValueConfig('gitkeep', false)) {
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
        if (!$this->namespace) {
            $dataInfo = $this->getDataInfo();
            if ($dataInfo) {
                $this->namespace =  $dataInfo->getValue('namespace');
            } else {
                $baseTypeName = $this->getBaseType();
                $namespace = config('sokeio.platform.' . $baseTypeName . '.namespace');
                $this->namespace =  $namespace . "\\" . $this->getStudlyNameReplacement();
            }
        }
        return $this->namespace;
    }
    /**
     * Get replacement for $BASE_TYPE_LOWER_NAME$.
     *
     * @return string
     */
    public function getBaseTypeLowerNameReplacement()
    {
        return Str::lower($this->getBaseType());
    }
    /**
     * Get replacement for $BASE_TYPE$.
     *
     * @return string
     */
    public function getBaseTypeReplacement()
    {
        return Str::studly($this->getBaseType());
    }
    /**
     * Get replacement for $VENDOR$.
     *
     * @return string
     */
    protected function getVendorReplacement()
    {
        return config('sokeio.composer.vendor');
    }
    /**
     * Get replacement for $AUTHOR_NAME$.
     *
     * @return string
     */
    protected function getAuthorNameReplacement()
    {
        return config('sokeio.composer.author.name');
    }
    /**
     * Get replacement for $AUTHOR_EMAIL$.
     *
     * @return string
     */
    protected function getAuthorEmailReplacement()
    {
        return config('sokeio.composer.author.email');
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
