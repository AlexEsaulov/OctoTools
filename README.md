# OctoTools

```git submodule add https://github.com/AlexEsaulov/OctoTools.git plugins/esaulov/octotools
git submodule init
git submodule update
php artisan october:up```

Example:
```$ php artisan make:plugin:migration vendor.name migration_name
$ php artisan make:plugin:migration vendor.name migrationName ModelName
$ php artisan make:plugin:migration vendor.name migrationName ModelName --update-version #adding to version.yaml
$ php artisan make:plugin:migration vendor.name migrationName ModelName --update-version --add-timestamp #added timestamp to migrationName```
