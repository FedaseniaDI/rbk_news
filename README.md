# Тестовое приложение на Luminas

## Подготовка
запустите
```bash
$ composer install 
```

для запуска нужно создать БД MYSQL

после в конфигах /config/autoload/global.php прописать данные подключения, 

так же 
создайте файл /config/autoload/local.php с содержимым как у /config/autoload/local.php.dist
и в созданном файле введите имя пользователя и пароль для доступа к БД
настройте свой веб сервер в корень /public к точке входа /public/index.php 
пропишите в файле /data/load_db.php данные доступа к БД, и запустите в терменала

```bash
$ php data/load_db.php
```

## Запуск окружения через Docker

Для запуска введите в терминале
```bash
$ docker-compose up -d
```
После успешного запуска приложение будет доступно по адресу http://localhost

PhpMyAdmin доступен по адресу http://localhost:8183