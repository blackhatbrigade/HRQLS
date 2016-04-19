<?php

require_once __DIR__ . '/../vendor/autoload.php';

$bootstrap = new HRQLS\Bootstrap(new Silex\Application());

$bootstrap->loadConfig();

$bootstrap->loadModules();

$bootstrap->startRenderEngine();

$bootstrap->connectDatabases();

$bootstrap->registerRoutes();

$bootstrap->startupSite();
