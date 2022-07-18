1. rename .env.example, default.conf.example
2. Change .env mailer and default.conf hostname
3. `docker-compose up --build `
4. `composer install` || `composer install --no-dev --optimize-autoloader` || `composer update`
5. from php container run  `php bin/console doctrine:migrations:migrate`
6. `yarn install` in 'app' 
7. `yarn build`
8. `php bin/console doctrine:fixtures:load`
9. go to http://localhost