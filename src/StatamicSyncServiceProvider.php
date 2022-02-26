<?php

namespace InsightMedia\StatamicSync;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use InsightMedia\StatamicSync\Commands\StatamicSyncCommand;

class StatamicSyncServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('statamic-sync')
            ->hasConfigFile()
            ->hasCommand(StatamicSyncCommand::class);
    }
}
