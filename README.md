Hour managament
------------------

application to track your time


Instructions
---

1. checkout
2. curl -sS https://getcomposer.org/installer | php # install composer
3. php composer.phar install # install project dependencies
4. cp config/db.local.php.dist config/db.local.php
5. cat vendor/zf-commons/zfc-user/data/schema.sqlite.sql | sqlite3 data/database.db
6. php -S 0.0.0.0:8080 -t public/