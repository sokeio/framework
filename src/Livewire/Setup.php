<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Artisan;
use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Locale;
use Sokeio\Facades\Module;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\Laravel\JsonData;
use Sokeio\Locales\Extractor\TranslationFinder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Setup extends Component
{
    public $systemVersion = '';
    public $lang = 'en';
    public $timezone;
    // db
    public $dbConnection = 'mysql';
    public $dbHost = '127.0.0.1';
    public $dbPort = '3306';
    public $dbName;
    public $dbUsername;
    public $dbPass;
    // acc
    public $accName;
    public $accEmail;
    public $accPass;
    //site
    public $siteName;
    public $siteDescription;

    public $stepIndex = 0;
    public $stepMax = 4;
    public $languages = [];
    public $activeModules = [];
    public $activePlugins = [];
    public $activeTheme = 'none';
    public function updatedLang()
    {
        Locale::switchLocale($this->lang);
    }
    public function stepNext()
    {
        if ($this->stepIndex == 1) {
            $this->validate([
                'dbHost' => ['required'],
                'dbPort' => ['required'],
                'dbName' => ['required'],
                'dbUsername' => ['required'],
                'dbPass' => ['required'],
            ]);
            if (!$this->checkDatabaseConnection()) {
                $this->showMessage('Connection to database fail!');
                return false;
            }
        }
        if ($this->stepIndex == 2) {
            $this->validate([
                'accName' => ['required'],
                'accEmail' => ['required'],
                'accPass' => ['required'],
            ]);
        }
        if ($this->stepIndex == 3) {
            $this->validate([
                'siteName' => ['required'],
                'siteDescription' => ['required'],
            ]);
        }
        if ($this->stepIndex <= $this->stepMax) {
            $this->stepIndex++;
        }
    }
    public function stepBack()
    {
        $this->clearValidation();
        if ($this->stepIndex > 0) {
            $this->stepIndex--;
        }
    }
    public function stepFinish()
    {

        $this->createDataInDB();
        $this->AcitveExtentions();
        $path = public_path(platformPath());
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }
        Locale::tableToFileJson();
        Platform::setEnv([
            'DB_CONNECTION' => $this->dbConnection,
            'DB_HOST' => $this->dbHost,
            'DB_PORT' => $this->dbPort,
            'DB_DATABASE' => $this->dbName,
            'DB_USERNAME' => $this->dbUsername,
            'DB_PASSWORD' => $this->dbPass,
            'APP_NAME' => $this->siteName,
            'APP_URL' => url(''),
            'SOKEIO_SETUP' => false,
        ]);
        return redirect('/');
    }
    /**
     * -------------------------------------------------------------------------------
     *  checkDatabaseConnection
     * -------------------------------------------------------------------------------
     **/
    public function checkDatabaseConnection()
    {
        $connection = $this->dbConnection;
        $database_host = $this->dbHost;
        $database_port = $this->dbPort;
        $database_name = $this->dbName;
        $database_username = $this->dbUsername;
        $database_password = $this->dbPass;
        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver'   => $connection,
                        'host'     => $database_host,
                        'port'     => $database_port,
                        'database' => $database_name,
                        'username' => $database_username,
                        'password' => $database_password,
                    ]),
                ],
                'migrations' => 'migrations'
            ],
        ]);

        DB::purge();

        try {

            DB::connection()->getPdo();

            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function createDataInDB()
    {
        if (!$this->checkDatabaseConnection()) {
            $this->showMessage('Connection to database fail!');
            return false;
        }
        Artisan::call('migrate', array('--force' => true));

        $roleModel = (config('sokeio.model.role', \Sokeio\Models\Role::class));
        $userModel = (config('sokeio.model.user', \Sokeio\Models\User::class));
        $roleAdmin = $roleModel::where('slug', $roleModel::SupperAdmin())->first();
        if (!$roleAdmin) {
            $roleAdmin = new $roleModel;
            $roleAdmin->name = $roleModel::SupperAdmin();
            $roleAdmin->slug = $roleModel::SupperAdmin();
            $roleAdmin->status = true;
            $roleAdmin->save();
        }
        $userAdmin = $userModel::where('email', $this->accEmail)->first();
        if (!$userAdmin) {
            $userAdmin = new $userModel;
        }
        $userAdmin->name = $this->accName;
        $userAdmin->email = $this->accEmail;
        $userAdmin->password = $this->accPass;
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);

        setSetting(PLATFORM_SITE_DESCRIPTION, $this->siteDescription);
        setSetting(PLATFORM_SITE_NAME, $this->siteName);
    }
    private function activeItem($item)
    {
        if ($item) {
            $item->active();
            $item->doRegister();
            $item->doBoot();
        }
    }
    public function AcitveExtentions()
    {
        foreach ($this->activeModules as $key => $value) {
            if ($value) {
                $this->activeItem(Module::find($key));
            }
        }
        foreach ($this->activePlugins as $key => $value) {
            if ($value) {
                $this->activeItem(Plugin::find($key));
            }
        }
        if ($this->activeTheme) {
            $this->activeItem(Theme::find($this->activeTheme));
        }
        Artisan::call('migrate', array('--force' => true));
    }
    public function mount()
    {
        Cache::clear();
        TranslationFinder::updateToJson();
        Assets::setTitle('System Setup');
        Theme::layout('none');
        Assets::Theme('tabler');
        Assets::AddCss('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');
        $this->lang = Locale::currentLocale();
        $this->languages = JsonData::getJsonFromFile(__DIR__ . '/../../database/contents/languages.json');
        $this->dbConnection = env('DB_CONNECTION', 'mysql');
        $this->dbHost = env('DB_HOST', '127.0.0.1');
        $this->dbPort = env('DB_PORT', '3306');
        $this->dbName = env('DB_DATABASE', 'forge');
        $this->dbUsername = env('DB_USERNAME', 'forge');
        $this->dbPass = env('DB_PASSWORD', '');
        $this->systemVersion = sokeioVersion() ?? 'v1.0.0';
    }
    public function render()
    {
        return view(
            'sokeio::setup',
            [
                'modules' => Module::getAll(),
                'plugins' => Plugin::getAll(),
                'themes' => Theme::getAll()
            ]
        );
    }
}
