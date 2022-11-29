Getting started
===============

Prerequisites
-------------

This repository recommend use VUE CLI and Composer.  
**Protip:** It is highly recommended to use HTTPS.

Installation
------------

### Backend


* Install all dependecy by composer in directory _onebb-backend_ 
* Configure your web server to point on _onebb-backend/public/index.php_ 
* In _onebb-backend_ create own .env.local 

``` env
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=YOUROWNSECRETCODEFORSYMFONYAPP11
###< symfony/framework-bundle ###

###> symfony/mailer ###
# MAILER_DSN=smtp://localhost
###< symfony/mailer ###

###> doctrine/doctrine-bundle ###
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="mysql://username:password@host:3306/db_name?serverVersion=5.7"
# DATABASE_URL="postgresql://symfony:ChangeMe@127.0.0.1:5432/app?serverVersion=13&charset=utf8"
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=YOUROWNJWTPASSPHRASE111111111111
###< lexik/jwt-authentication-bundle ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN='*'
###< nelmio/cors-bundle ###

###> symfony/lock ###
LOCK_DSN=semaphore
###< symfony/lock ###

###> onebb configuration ###
ACP_URL="acp"
BASE_URL="/"
###< onebb configuration ###
```

* migrate DB and Fixtures using Symfony Console:

``` bash
php bin/console doctrine:migrations:migrate  
php bin/console doctrine:fixtures:load
```
> default user: _admin_  
> default password: _admin_

### Frontend

* Open VUE CLI and add projects from directories _onebb-admin_ and _onebb-front_
* edit .env in directories _onebb-admin_ and _onebb-front_
> VUE_APP_BASE_SHEME  
this variable should point to your domian where 
* turn on vue serve 
