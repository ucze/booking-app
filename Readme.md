###### Requirements
`php 7.4` + `mysql` ( https://symfony.com/doc/4.4/setup.html#technical-requirements )
###### Installation
- `composer install`
- `php bin/console doctrine:fixtures:load`

###### How to run locally
```
docker-compose up -d
docker-compose exec php-fpm composer install
docker-compose exec php-fpm php bin/console doctrine:fixtures:load -q
```

###### Api documentation:
http://localhost/api/doc

###### Todo:
- Add authentication - ex (https://symfony.com/doc/4.4/security/guard_authentication.html)
- Refactor services
- Switch to api platform?
- Update .env file, move db creds to local