<?php

namespace Sokeio\Livewire;

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

class Setup extends Component
{
    public $system_version = '';
    public $lang = 'en';
    public $timezone;
    // db
    public $db_connection = 'mysql';
    public $db_host = '127.0.0.1';
    public $db_port = '3306';
    public $db_name;
    public $db_username;
    public $db_pass;
    // acc
    public $acc_name;
    public $acc_email;
    public $acc_pass;
    //site
    public $site_name;
    public $site_description;

    public $step_index = 0;
    public $step_max = 4;
    public $languages = [];
    public $active_modules = [];
    public $active_plugins = [];
    public $active_theme = 'none';
    public function updatedLang()
    {
        Locale::SwitchLocale($this->lang);
    }
    public function stepNext()
    {
        if ($this->step_index == 1) {
            $this->validate([
                'db_host' => ['required'],
                'db_port' => ['required'],
                'db_name' => ['required'],
                'db_username' => ['required'],
                'db_pass' => ['required'],
            ]);
            if (!$this->updateEnvDB()) {
                return;
            }
        }
        if ($this->step_index == 2) {
            $this->validate([
                'acc_name' => ['required'],
                'acc_email' => ['required'],
                'acc_pass' => ['required'],
            ]);
            ob_start();
            run_cmd(base_path(''), 'php artisan migrate');
            ob_get_clean();
        }
        if ($this->step_index == 3) {
            $this->validate([
                'site_name' => ['required'],
                'site_description' => ['required'],
            ]);
        }
        if ($this->step_index >= $this->step_max) return;
        $this->step_index++;
    }
    public function stepBack()
    {
        $this->clearValidation();
        if ($this->step_index <= 0) return;
        $this->step_index--;
    }
    public function stepFinish()
    {
        $this->createDataInDB();
        $this->AcitveExtentions();
        Locale::FileJsonToTable();
        Platform::setEnv([
            'BYTE_SETUP' => false,
        ]);
        return redirect('/');
    }
    /**
     * -------------------------------------------------------------------------------
     *  checkDatabaseConnection
     * -------------------------------------------------------------------------------
     **/
    public function checkDatabaseConnection($database_host, $database_port, $database_name, $database_username, $database_password, $connection = 'mysql')
    {
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

    public function updateEnvDB()
    {
        if (!$this->checkDatabaseConnection($this->db_host, $this->db_port, $this->db_name, $this->db_username, $this->db_pass, $this->db_connection)) {
            $this->showMessage('Connection to database fail!');
            return false;
        }

        Platform::setEnv([
            'DB_CONNECTION' => $this->db_connection,
            'DB_HOST' => $this->db_host,
            'DB_PORT' => $this->db_port,
            'DB_DATABASE' => $this->db_name,
            'DB_USERNAME' => $this->db_username,
            'DB_PASSWORD' => $this->db_pass,
            'APP_NAME' => $this->site_name,
            'APP_URL' => url('')
        ]);
        return true;
    }

    public function createDataInDB()
    {
        $roleModel = (config('byte.model.role', \Sokeio\Models\Role::class));
        $userModel = (config('byte.model.user', \Sokeio\Models\User::class));
        $roleAdmin = $roleModel::where('slug', $roleModel::SupperAdmin())->first();
        if (!$roleAdmin) {
            $roleAdmin = new $roleModel;
            $roleAdmin->name = $roleModel::SupperAdmin();
            $roleAdmin->slug = $roleModel::SupperAdmin();
            $roleAdmin->status = true;
            $roleAdmin->save();
        }
        $userAdmin = $userModel::where('email', $this->acc_email)->first();
        if (!$userAdmin)
            $userAdmin = new $userModel;
        $userAdmin->name = $this->acc_name;
        $userAdmin->email = $this->acc_email;
        $userAdmin->password = $this->acc_pass;
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);
    }
    public function AcitveExtentions()
    {
        foreach ($this->active_modules as $key => $value) {
            if ($value) {
                Module::find($key)?->Active();
            }
        }
        foreach ($this->active_plugins as $key => $value) {
            if ($value) {
                Plugin::find($key)?->Active();
            }
        }
        if ($this->active_theme) {
            Theme::find($this->active_theme)?->Active();
        }
    }
    public function mount()
    {
        Cache::clear();
        TranslationFinder::updateToJson();
        Theme::setTitle('System Setup');
        Theme::Layout('none');
        Assets::Theme('tabler');
        Assets::AddCss('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');
        $this->lang = Locale::CurrentLocale();
        $this->languages = JsonData::getJsonFromFile(__DIR__ . '/../../database/contents/languages.json');
        $this->db_connection = env('DB_CONNECTION', 'mysql');
        $this->db_host = env('DB_HOST', '127.0.0.1');
        $this->db_port = env('DB_PORT', '3306');
        $this->db_name = env('DB_DATABASE', 'forge');
        $this->db_username = env('DB_USERNAME', 'forge');
        $this->db_pass = env('DB_PASSWORD', '');
        $this->system_version = Module::find('byte')?->version ?? 'v1.0.0';
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
