<?php

namespace Lkt\Users;

use Lkt\Commander\Commander;
use Lkt\Phinx\PhinxConfigurator;
use Lkt\Users\Console\Commands\SetupTranslationsCommand;
use function Lkt\Tools\Requiring\requireFiles;

requireFiles([
    __DIR__.'/Config/Schemas/*.php',
]);

if (php_sapi_name() == 'cli') {
    PhinxConfigurator::addMigrationPath(__DIR__ . '/../database/migrations');

    Commander::register(new SetupTranslationsCommand());
}