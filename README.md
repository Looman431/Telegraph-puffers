# Про бота

Бот предназначен для телеграм чата Puffers Community, по факту он сейчас просто лежит ввиде репозитория на гите, но если вам захочется его задеплоить то снизу инструкция

Сам бот построен на фреймворке [Laravel](https://laravel.com/) для PHP при помощи пакета [Telegraph](https://github.com/defstudio/telegraph), работает через [Docker](https://www.docker.com/) при помощи пакета [Laravel Sail](https://laravel.com/docs/12.x/sail) 

> Для запуска обязателен докер

## Запуск проекта

  Для начала нужен сам [ларавель](https://laravel.com/docs/12.x) со всеми зависимостями<br>
```
   /bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"

   Если композер и все зависимости уже стоят                                                
  
   composer global require laravel/installer
```

 Устанавливаем Sail и Telegraph внутрь проекта
 ```
  composer require laravel/sail --dev

  php artisan sail:install

  Ставим telegraph

  composer require defstudio/telegraph
 ```


### Настройка

  Дальше настраиваем уже самого бота<br>
  
  Для начала запускаем все миграции
  ```
  sail artisan vendor:publish --tag="telegraph-migrations"
  
  sail artisan migrate
  ```

  Дальше настраиваем самого [бота](https://docs.defstudio.it/telegraph/v1/quickstart/new-bot) внутри телеграма<br>
  
  Получив токена бота добавляем его в приложение<br>
  
  `php artisan telegraph:new-bot`
   ![fotka](/resources/image.png "aboba")

  Впринципе если вы ставили это все на какую ту машину, то нужно в вашем .env в APP_URL вставить адресс, и прописать `./vendor/bin/sail up` внутри проекта и проверить работоспособность<br>
  
>  Если у вас стоит какой то терминал то зайдите в ~/.zshrc` или ~/.bashrc и вставьте
  
>  alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
  
>  Теперь можно прописывать просто sail up, sail artisan ...
  
  Дальше пробуем писать в телеграм и понимаем что ничего нету, потому-что надо сетнуть вебхук<br>
  ```
  sail artisan telegraph:set-webhook {bot_id}
  В нашем случии айди бота можно оставить пустым
  ```


  Но если вы ставили на свою локальную машину а не на хост то есть прога которая позволяет проксировать порт на определенный домен [NGROK](https://dashboard.ngrok.com/get-started/setup/linux)<br>
  Регаемся, скачиваем к себе, связываем, и теперь можно проксировать порт к примеру `80`<br>
  `ngrok http 80`
  Дальше ngrok выдадит какой-то адрес, который мы уже вставляем в<br> 
  ```
  ..\Telegraph-puffers\.env
  APP_URL=ctrl+v
  ```

> После каждого перезапуска нгрока не забываем менять адресс, и каждый раз сетать вебхук 
  
# Конец
Ну как-то так, короче обычно ритуал запуска у меня такой:
- Запускаю ngrok
- Вставляю новый адресс в .env
- Запускаю докер
- Запускаю приложение через `sail up`
- делаю `sail artisan telegraph:set-webhook`
- пишу /test в чате с ботом для теста

Документация говна немного, потому-что много чего не пояснил но не думаю что кто то деплоить проект будет
