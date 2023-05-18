<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



## Тестовое задание для компании Logsis

Для запуска системы необходимо следующее:

- Nginx
- Php 7.3 и выше
- Mysql (в иделале для такой задачи использовать ClickHouse)
- Erlang (тестировал на версии 23.2.6)
- RabbitMQ (тестировал на версии 3.8.9)

Для запуска получения сообщений из RabbitMQ необходимо:

- в локальной среде с корня проекта запустить команду "php artisan rabbit-receive:start имя_очереди"
- в prod среде создать системный юнит с запуском вышеуказанной команды и добавить его в автозагрузку linux

имя_очереди в консоли должно соответствовать env RABBIT_MQ_QUEUE_NAME

## Краткое описание системы:

1. В мидлваере MeasureResponseTime измеряется время выполнения всех методов контроллеров и эта информация передается в RabbitMQ
2. RabbitMQ при получении информации о контроллере, методе, времени выполнения записывает информацию в БД
