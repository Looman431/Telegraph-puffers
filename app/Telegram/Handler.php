<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler
{
    public function start(): void{
        $this->reply('Бот работает вроде как');
    }
}
