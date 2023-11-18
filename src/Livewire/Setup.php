<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Facades\Assets;
use BytePlatform\Facades\Locale;
use BytePlatform\Facades\Module;
use BytePlatform\Facades\Platform;
use BytePlatform\Facades\Plugin;
use BytePlatform\Facades\Theme;
use BytePlatform\Laravel\JsonData;

class Setup extends Component
{
    public $lang = 'en';
    public $timezone;
    public $db_type = 'mysql';
    public $host = '127.0.0.1';
    public $database;
    public $username;
    public $password;
    public $acc_name;
    public $acc_email;
    public $acc_pass;
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
        if ($this->step_index >= $this->step_max) return;
        $this->step_index++;
    }
    public function stepBack()
    {
        if ($this->step_index <= 0) return;
        $this->step_index--;
    }
    public function stepFinish()
    {
        $this->showMessage(json_encode($this->active_plugins));
        $this->updateEnv();
        // return redirect('/');
    }

    public function updateEnv()
    {
        Platform::setEnv([
            'DB_CONNECTION' => $this->db_type,
            'DB_HOST' => $this->host,
            'DB_DATABASE' => $this->database,
            'DB_USERNAME' => $this->username,
            'DB_PASSWORD' => $this->password,
            'APP_NAME' => $this->site_name,
            'APP_URL' => url('')
        ]);
        //php artisan cache:clear
        run_cmd(base_path(''), 'php artisan key:generate');
        run_cmd(base_path(''), 'php artisan cache:clear');
        run_cmd(base_path(''), 'php artisan migreate');
        $this->createUser();
    }

    public function createUser()
    {
        $roleModel = (config('byte.model.role', \BytePlatform\Models\Role::class));
        $userModel = (config('byte.model.user', \BytePlatform\Models\User::class));
        $roleAdmin = new $roleModel;
        $roleAdmin->name = $roleModel::SupperAdmin();
        $roleAdmin->slug = $roleModel::SupperAdmin();
        $roleAdmin->status = true;
        $roleAdmin->save();
        $userAdmin = new $userModel;
        $userAdmin->name = $this->acc_name;
        $userAdmin->email = $this->acc_email;
        $userAdmin->password = $this->acc_pass;
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);
    }
    public function mount()
    {
        Theme::setTitle('System Setup');
        Assets::Theme('tabler');
        Assets::AddCss('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');
        $this->lang = Locale::CurrentLocale();
        $this->languages = JsonData::getJsonFromFile(__DIR__ . '/../../database/contents/languages.json');
    }
    public function render()
    {
        return view(
            'byte::setup',
            [
                'modules' => Module::getAll(),
                'plugins' => Plugin::getAll()
            ]
        );
    }
}
