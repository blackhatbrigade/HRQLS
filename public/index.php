<?php

require_once __DIR__ . '/../vendor/autoload.php';

$bootstrap = new HRQLS\Bootstrap(new Silex\Application());

$bootstrap->loadConfig();

$bootstrap->startRenderEngine();

$bootstrap->registerRoutes();

$bootstrap->startupSite();
