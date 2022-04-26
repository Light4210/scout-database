1. `docker-compose up --build `
2. `mkdir var`
3. `chown -R www-data:www-data var`
4. `composer install`
5. from php container run  `php bin/console doctrine:migrations:migrate`
6. `yarn install` in 'app' 7 
7. `yarn build`
-------------------------
if you need test data from php container run  `php bin/console doctrine:fixtures:load`
-------------------------
