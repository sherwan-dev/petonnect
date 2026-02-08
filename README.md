# Petonnect
Your pet's social life, organized.

## Installation

### (Some) Requirements 

  * PHP 8.4 or higher;
  * Node.js and npm
  * Composer 
  * and the [usual Symfony application requirements](https://symfony.com/doc/current/setup.html#technical-requirements).

### Install dependencies
```
composer install 
npm install
``` 

### Compile assets
```
npm run dev
```

### Initialize DATABASE_URL
```
DATABASE_URL=mysql://<user>:<password>@<host>/<database_name>?serverVersion=8.0
```
### Initialize APP_SECRET
```
APP_SECRET=<YOUR_SECRET_KEY>
```

### Create database
```
bin/console doctrine:database:create
```

### Create schema
```
bin/console doctrine:schema:create
```

### Configure the mailer(MAILER_DSN) for registration & email verification:
For example: mailpit (using docker-image: axllent/mailpit:latest)