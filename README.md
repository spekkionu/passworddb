# Website Password Database

[![Build Status](https://travis-ci.org/spekkionu/passworddb.png?branch=master)](https://travis-ci.org/spekkionu/passworddb)

## Install

Checkout code from repository.
```bash
git clone git://github.com/spekkionu/passworddb.git
cd passworddb
git submodule update --init --recursive
```

Install Project Dependencies
```bash
curl -s https://getcomposer.org/installer | php
php composer.phar install
```

Setup Required Directories
```bash
mkdir system/logs
chmod -R 0664 system/logs
mkdir system/data/database
chmod -R 0774 system/data/database
```