<?php
/**
 * Part of the modules system of Hercules.
 *
 * @package HRQLS\helpers
 * @subpackage ModuleSystem
 * @author Jason Bennett
 */

namespace HRQLS\Helpers;

/**
 * The helper class that validates a module.
 */
class ConfigValidation
{
    /**
     * The required fields for the configuration object.
     *
     * @param array
     */
    const STRUCTURE_REQUIREMENTS = [
        'author' => 'string',
        'description' => 'string',
        'module_uri' => 'string',
        'module_name' => 'string',
        'routes' => [
            'url' => 'string',
            'controller' => 'string'
        ],
        'module_config' => 'object',
        'data' => 'object'
    ];

    private $config;

    private $routes;

    /**
     * Class constructor.
     *
     * @param string $config Location of something.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->routes = [];
    }

    /**
     * 
     */
    public function validateModule()
    {
        $flag = $this->validateFields();

        return $flag;
    }

    public function getEndpoints()
    {
        return $this->routes;
    }

    /**
     * Using the Structure requirements, this function validates that everything is in the format expected.
     *
     * @return boolean
     */
    private function validateFields()
    {
        foreach (self::STRUCTURE_REQUIREMENTS as $key => $value) {
            if (!isset($this->config->$key)) {
                throw new \Exception($key . " value is not set in the config.json file!");
            }

            // Hate doing this....
            if ($key === 'routes') {
                $this->validateRoutes();
            } else {
                if (gettype($this->config->$key) !== self::STRUCTURE_REQUIREMENTS[$key]) {
                    throw new \Exception($key . " value is not valid!  Expected " . self::STRUCTURE_REQUIREMENTS[$key] . " but got " . gettype($this->config->$key));
                }
            }
        }
        return true;
    }

    /**
     * Using the Structure requirements for endpoint specifications, this function validates each endpoint configuration.
     *
     * @return void
     */
    private function validateRoutes()
    {
        foreach ($this->config->routes as $key => $value) {
            foreach (self::STRUCTURE_REQUIREMENTS['routes'] as $subKey => $subValue) {
                if (!isset($this->config->routes[$key]->$subKey)) {
                    throw new \Exception($key . " - route - " . $subKey . " value not set in the config.json file!");
                }

                if (gettype($this->config->routes[$key]->$subKey) !== $subValue) {
                    throw new \Exception(
                      $key . " - route - " . $subKey
                      . " value is not valid!  Expected "
                      . $subValue
                      . " but got " . gettype($this->config->routes->$subKey)
                    );
                }
            }

            array_push($this->routes, $this->config->routes[$key]);
        }
    }

    /**
     * Ensures that the controllers exist.
     *
     * @return boolean
     */
    public function checkForController($controllerName)
    {
        $moduleName = '\\Modules\\' . $controllerName;
        if (!class_exists($moduleName)) {
            throw new \Exception("Class " . $moduleName . " does not exist!");
        }
        echo "Controller: " . $controllerName . " Found";
        return true;
    }
}
