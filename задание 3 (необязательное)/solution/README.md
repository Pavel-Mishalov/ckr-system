# Заявки пользователям на Yii2

Сделано на шаблоне yii2-app-basic.

## Запуск

```
docker compose up -d --build
```

Потом открыть http://localhost:8082/

Перед стартом apache контейнер app прогоняет миграции: создаются таблицы user и request и заводятся пять тестовых пользователей.

Без докера: `composer install`, доступы к MySQL в config/db.php (или через переменные DB_HOST, DB_NAME, DB_USER, DB_PASS), `php yii migrate`, корень сайта - папка web.

Заявки сохраняются в таблицу request.
