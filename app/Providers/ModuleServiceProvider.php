<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    protected array $modules = [
        'Auth',
        'Doctor',
        'Pharmacy',
        'Prescription',
        'Inventory',
        'Sales',
        'Reports',
        'Admin',
    ];

    public function boot(): void
    {
        foreach ($this->modules as $module) {
            $this->bootModule($module);
        }
    }

    protected function bootModule(string $module): void
    {
        $modulePath = app_path("Modules/{$module}");

        if (file_exists("{$modulePath}/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulePath}/Routes/web.php");
        }

        if (file_exists("{$modulePath}/Routes/api.php")) {
            $this->loadRoutesFrom("{$modulePath}/Routes/api.php");
        }

        if (is_dir("{$modulePath}/Database/Migrations")) {
            $this->loadMigrationsFrom("{$modulePath}/Database/Migrations");
        } elseif (is_dir("{$modulePath}/Migrations")) {
            $this->loadMigrationsFrom("{$modulePath}/Migrations");
        }

        if (is_dir("{$modulePath}/Views")) {
            $this->loadViewsFrom("{$modulePath}/Views", strtolower($module));
        }
    }
}