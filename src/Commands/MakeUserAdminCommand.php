<?php

namespace Sokeio\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class MakeUserAdminCommand extends Command
{
    protected $name = 'so:make-user-admin';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['email', 'e', InputOption::VALUE_OPTIONAL, 'your email', 'admin@sokeio.com'],
            ['fullname', 'f', InputOption::VALUE_OPTIONAL, 'your fullname', 'nguyen van hau'],
        ];
    }
    protected function getArguments()
    {
        return [];
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (env('SOKEIO_MAKE_USER_ADMIN') !== true) {
            $this->info('SOKEIO_MAKE_USER_ADMIN is not enabled');
            return 0;
        }
        $email = $this->option('email');
        $name = $this->option('fullname');

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
        $userAdmin = $userModel::where('email', $email)->first();
        if (!$userAdmin) {
            $userAdmin = new $userModel;
        }
        $pass = str()->random(10);
        $userAdmin->name = $name;
        $userAdmin->email = $email;
        $userAdmin->password = $pass;
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);
        $this->info('User admin created: ' . $email . ' - ' . $pass);
        return 0;
    }
}
