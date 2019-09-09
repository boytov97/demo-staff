<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->formsDir,
        $config->application->libraryDir
    ]
)->register();

// Use composer autoloader to load vendor classes
require_once BASE_PATH . '/vendor/autoload.php';