<?php

namespace Sokeio\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class Init extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $roleModel = (config('sokeio.model.role', \Sokeio\Models\Role::class));
        $userModel = (config('sokeio.model.user', \Sokeio\Models\User::class));
        $roleAdmin = new $roleModel;
        $roleAdmin->name = $roleModel::SupperAdmin();
        $roleAdmin->slug = $roleModel::SupperAdmin();
        $roleAdmin->status = true;
        $roleAdmin->save();
        $userAdmin = new $userModel;
        $userAdmin->name = env('SOKEIO_PlATFORM_FULLNAME', "NGUYEN VAN HAU");
        $userAdmin->email = env('SOKEIO_PlATFORM_EMAIL', "admin@hau.xyz");
        $userAdmin->password = env('SOKEIO_PlATFORM_PASSWORD', "AdMin@123");
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);
    }
}
