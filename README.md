# Website Password Database
[![Build Status](https://github.com/spekkionu/passworddb/actions/workflows/laravel.yml/badge.svg?branch=master)](https://github.com/spekkionu/passworddb/actions/workflows/laravel.yml)

## Install

Checkout code from repository.
```bash
git clone git://github.com/spekkionu/passworddb.git
cd passworddb
```

Install Project Dependencies
```bash
curl -s https://getcomposer.org/installer | php
php composer.phar install --no-dev
```

Create and install database
```bash
touch storage/database/database.sqlite
php artisan migrate
```
