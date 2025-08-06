### Install
```shell
sudo php install-todo.php
```
From now on you have access to Commands: 
```shell
todo list
```

### Run all Tests
```shell
vendor/bin/phpunit
```

### Code Coverage
install xdebug
```shell
sudo apt install php-xdebug
php -m | grep xdebug
```

get code coverage
```shell
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text
```

