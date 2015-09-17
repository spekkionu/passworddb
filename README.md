# Website Password Database
[![Build Status](https://travis-ci.org/spekkionu/passworddb.png?branch=master)](https://travis-ci.org/spekkionu/passworddb)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e90621ea-3c05-4782-8361-1b847b9a1ad4/mini.png)](https://insight.sensiolabs.com/projects/e90621ea-3c05-4782-8361-1b847b9a1ad4)
[![Scrutinizer Continuous Inspections](https://scrutinizer-ci.com/g/spekkionu/passworddb/badges/general.png?s=e0dc42fae72a68b25fae28e134dacd91ce17002b)](https://scrutinizer-ci.com/g/spekkionu/passworddb/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/spekkionu/passworddb/badges/quality-score.png?s=26a15331752cfc36a30e530b3d1d528b384704e4)](https://scrutinizer-ci.com/g/spekkionu/passworddb/)
[![Code Coverage](https://scrutinizer-ci.com/g/spekkionu/passworddb/badges/coverage.png?s=8f907dc3fe122119da39d9b049a3f3cc601aa2f5)](https://scrutinizer-ci.com/g/spekkionu/passworddb/)

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

Install Public Dependencies
```bash
bower install
```

Create and install database
```bash
touch storage/database.sqlite
php artisan migrate
```
