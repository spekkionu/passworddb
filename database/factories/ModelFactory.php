<?php

use App\Models\Website;
use App\Models\FTP;
use App\Models\Database;
use App\Models\Admin;
use App\Models\ControlPanel;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Website::class, function ($faker) {
    return [
        'name' => $faker->unique()->company,
        'domain' => $faker->domainName,
        'url' => $faker->url,
        'notes' => $faker->paragraph,
    ];
});

$factory->define(Database::class, function ($faker) {
    return [
        'type' => $faker->randomElement(['mysql', 'sqlite', 'mssql', 'oracle', 'pgsql', 'access', 'other']),
        'hostname' => $faker->domainName,
        'username' => $faker->userName,
        'password' => $faker->password,
        'database' => $faker->domainWord,
        'url' => $faker->url,
        'notes' => $faker->paragraph,
    ];
});

$factory->define(FTP::class, function ($faker) {
    return [
        'type' => $faker->randomElement(['ftp', 'sftp', 'ftps', 'webdav', 'other']),
        'hostname' => $faker->domainName,
        'username' => $faker->userName,
        'password' => $faker->password,
        'path' => $faker->word,
        'notes' => $faker->paragraph,
    ];
});

$factory->define(ControlPanel::class, function ($faker) {
    return [
        'username' => $faker->userName,
        'password' => $faker->password,
        'url' => $faker->url,
        'notes' => $faker->paragraph,
    ];
});

$factory->define(Admin::class, function ($faker) {
    return [
        'username' => $faker->userName,
        'password' => $faker->password,
        'url' => $faker->url,
        'notes' => $faker->paragraph,
    ];
});
