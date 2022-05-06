1. `docker-compose up --build `
4. `composer install` || `composer install --no-dev --optimize-autoloader` || `composer update`
5. from php container run  `php bin/console doctrine:migrations:migrate`
6. `yarn install` in 'app' 
7. `yarn build`
-------------------------
if you need test data from php container run  `php bin/console doctrine:fixtures:load`
-------------------------
