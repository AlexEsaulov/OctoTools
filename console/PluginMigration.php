<?php

namespace Esaulov\OctoTools\Console;


use Exception;
use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Yaml;

class PluginMigration extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'make:plugin:migration';

    /**
     * @var string The console command description.
     */
    protected $description = 'Does something cool.';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $pluginDir = base_path('plugins/' . str_replace('.', DIRECTORY_SEPARATOR, $this->argument('pluginCode')));

        if (File::isDirectory($pluginDir) == false) {
            throw new Exception('Plugin ' . $this->argument('pluginCode') . ' not found!');
        }

        $parts = explode('.', $this->argument('pluginCode'));

        $migrationName = $this->argument('migrationName');

        if ($this->option('add-timestamp')) {
            $migrationName = date('YmdHis') . '_' . $migrationName;
        }

        $replace = [
            '{{studly_author}}' => studly_case($parts[0]),
            '{{studly_plugin}}' => studly_case($parts[1]),
            '{{studly_migration_name}}' => studly_case($migrationName)
        ];


        if ($this->argument('modelName')) {
            $class = '\\' . studly_case($parts[0]) . '\\' . studly_case($parts[1]) . '\\Models\\' . $this->argument('modelName');

            if (!class_exists($class)) {
                throw new Exception('Model not found!');
            }

            $replace['{{table_name}}'] = get_class_vars($class)['table'];

            if (!$replace['{{table_name}}']) {
                throw new Exception('Model property $table is empty!');
            }
        }

        $fileName = $pluginDir . DIRECTORY_SEPARATOR . 'updates' . DIRECTORY_SEPARATOR . snake_case($migrationName) . '.php';
        if (File::exists($fileName)) {
            throw new Exception('migration "' . basename($fileName) . '" already exists!');
        }

        $stub = File::get(__DIR__ . '/stubs/' . ($this->argument('modelName') ? 'update_table' : 'blank') . '.stub');

        $content = str_replace(array_keys($replace), array_values($replace), $stub);


        if ($this->option('auto-version')) {
            $versionFile = $pluginDir . DIRECTORY_SEPARATOR . 'updates' . DIRECTORY_SEPARATOR . 'version.yaml';

            $versions = Yaml::parseFile($versionFile);

            $lastVersion = array_keys($versions)[sizeof($versions) - 1];

            $x = explode('.', $lastVersion);
            $x[sizeof($x) - 1]++;

            $newVersion = join('.', $x);

            $versionContent = trim(File::get($versionFile));

            $versionContent .= PHP_EOL . $newVersion . ':' . PHP_EOL . '  - Run table migrations' . PHP_EOL . '  - ' . basename($fileName) . PHP_EOL;

            File::put($versionFile, $versionContent);
        }

        File::put($fileName, $content);

        $this->output->writeln('Created migration ' . basename($fileName));
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['pluginCode', InputArgument::REQUIRED, ''],
            ['migrationName', InputArgument::REQUIRED, ''],
            ['modelName', InputArgument::OPTIONAL, ''],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['add-timestamp', null, InputOption::VALUE_NONE, ''],
            ['auto-version', null, InputOption::VALUE_NONE, ''],
        ];
    }

}