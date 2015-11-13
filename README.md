# OctoTools
Extending artisan commands
## Installation:

```console
$ git submodule add https://github.com/AlexEsaulov/OctoTools.git plugins/esaulov/octotools
$ git submodule init
$ git submodule update
$ php artisan october:up
```

## Examples:

```console
$ php artisan make:plugin:migration vendor.pluginName migrationName # added plugins/vendor.pluginName/updates/migration_name.php
$ php artisan make:plugin:migration vendor.pluginName migrationName ModelName # added migration_name.php with Schema::table(...)
$ php artisan make:plugin:migration vendor.pluginName migrationName ModelName --auto-version # added to version.yaml
$ php artisan make:plugin:migration vendor.pluginName migrationName ModelName --auto-version --add-timestamp # added timestamp to migrationName
```
