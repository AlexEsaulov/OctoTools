<?php namespace Esaulov\OctoTools;

use Esaulov\OctoTools\Console\PluginMigration;
use System\Classes\PluginBase;

/**
 * OctoTools Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'OctoTools',
            'description' => 'Extending console commands',
            'author' => 'Alexandr Esaulov',
            'icon' => 'icon-leaf'
        ];
    }

    public function register()
    {
        $this->registerConsoleCommand('make:plugin:migration', PluginMigration::class);
    }
}
