<?php
/**
 * The module support mechanism.
 * @package HRQLS/Controllers
 */

namespace HRQLS\Controllers;

use HRQLS\Helpers\ConfigValidation;
use HRQLS\Models\ModuleModel;

/**
 * This class manages the loading of modules found in the hercules install.
 */
class ModulesController
{
    /**
     * The list of modules currently available.
     *
     * @var array
     */
    private $modules = [];

    private $modulesPath;

    /**
     * Modules controller constructor.
     */
    public function __construct()
    {
        $this->modulesPath = __DIR__ . '/../../Modules';
    }

    public function loadModules()
    {
      $this->locateModules();
    }

    /**
     * Scans through the modules director searching for modules.
     *
     * @return void
     */
    private function locateModules()
    {
        $dirList = array_diff(scandir($this->modulesPath), array('..', '.'));

        foreach ($dirList as $entry) {
            $candidate = new ModuleModel($this->modulesPath . '/' . $entry);
            if ($candidate->validate()) {
                echo "Stuff works!";
            } else {
                echo "Got a False!";
            }
        }

        exit;
    }

}
