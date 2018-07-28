<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\User\UserRepositoryContract::class,
            \App\Repositories\User\UserRepository::class
        );
        /*$this->app->bind('UserRepositoryContract', function ($app) {
            return new \App\Repositories\User\UserRepositoryContract($app['UserRepositoryContract']);
        });*/
        $this->app->bind(
            \App\Repositories\Role\RoleRepositoryContract::class,
            \App\Repositories\Role\RoleRepository::class
        );

        //绑定PermissionRepository
        $this->app->bind(
            \App\Repositories\Permission\PermissionRepositoryContract::class,
            \App\Repositories\Permission\PermissionRepository::class
        );

        //绑定PackageRepository
        $this->app->bind(
            \App\Repositories\Package\PackageRepositoryContract::class,
            \App\Repositories\Package\PackageRepository::class
        );

        //绑定PackageInfoRepository
        $this->app->bind(
            \App\Repositories\PackageInfo\PackageInfoRepositoryContract::class,
            \App\Repositories\PackageInfo\PackageInfoRepository::class
        );

        //绑定ImgRepository
        $this->app->bind(
            \App\Repositories\Image\ImageRepositoryContract::class,
            \App\Repositories\Image\ImageRepository::class
        );

        //绑定managerRepository
        $this->app->bind(
            \App\Repositories\Manager\ManagerRepositoryContract::class,
            \App\Repositories\Manager\ManagerRepository::class
        );

        //绑定InfoDianxinRepository
        $this->app->bind(
            \App\Repositories\InfoDianxin\InfoDianxinRepositoryContract::class,
            \App\Repositories\InfoDianxin\InfoDianxinRepository::class
        );

        //绑定InfoSelfRepository
        $this->app->bind(
            \App\Repositories\InfoSelf\InfoSelfRepositoryContract::class,
            \App\Repositories\InfoSelf\InfoSelfRepository::class
        );
    }
}
