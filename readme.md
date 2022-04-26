1. `docker-compose up --build `
2. from php container run  `php bin/console doctrine:migrations:migrate`
-------------------------
--if you need test data--
3. from php container run  `php bin/console doctrine:fixtures:load`
-------------------------
