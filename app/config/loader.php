<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces([
    'Single\Models'      => $config->application->modelsDir,
    'Single\Controllers' => $config->application->controllersDir,
    'Single\Forms'       => $config->application->formsDir,
    'Single'             => $config->application->libraryDir
])->register();
