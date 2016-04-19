<?php
/**
 * The module support mechanism.
 * @package HRQLS/Controllers
 */

namespace HRQLS\Models;

use HRQLS\Helpers\ConfigValidation;

/**
 * This class that manages an individual module.
 */
class ModuleModel
{
    /**
     * The location of the module.
     *
     * @var string
     */
    private $location;

    private $routes;

    /**
     * The Modules constructor.
     *
     * @param string $location Location of the module.
     *
     * @return void
     */
    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * Validates a given directory has the correct structure for a module.
     *
     * @param string $dir The directory name.
     *
     * @return boolean
     */
    public function validate()
    {
        // check for the config file.
        $config = file_get_contents($this->location . '/config.json');
        if ($config === false) {
            throw new \Exception("Does not have a config.json in Module.");
        }

        // Check that the config file is formatted correctly.
        $configData = json_decode($config);
        if ($configData === false || $configData === NULL) {
            throw new \Exception("config.json is not valid json.");
        }

        $validator = new ConfigValidation($configData);
        if (!$validator->validateModule()) {
            throw new \Exception("config.json is not valid!");
        }

        $this->routes = $validator->getEndpoints();

        foreach ($this->routes as $endpoint) {
            $validator->checkForController($endpoint->controller);
        }

        return true;
    }

    /**
     * Validates that the config file has the minimum parameters of a module config.
     *
     * @param stdObject $config The config file in object form.
     *
     * @return void
     */
    private function validateConfig(stdObject $config)
    {
        
    }
}
