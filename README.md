# Example for Event Sourcing

Репозиторий содержит примеры для статьи [Event Sourcing - что там после баланса пользователя?](https://timofeev.expert/posts/event-sourcing/)

## Запуск

1) Поднять контейнеры выполните `make up`
2) `make shell` - подключиться к терминалу контейнера с приложением
3) сгенерировать ключи для генерации jwt токена `bin/console lexik:jwt:generate-keypair`
4) установить миграции `bin/console doctrine:migrations:migrate`

Запуск тестов:
1) `composer run tests:unit`  
2) `composer run tests:integration`  
