<?php

namespace Lkt\Translations;

use Lkt\Phinx\PhinxConfigurator;
use function Lkt\Tools\Requiring\requireFiles;

requireFiles([
    __DIR__.'/Config/Schemas/*.php',
]);

if (php_sapi_name() == 'cli') {
    PhinxConfigurator::addMigrationPath(__DIR__ . '/../database/migrations');
}