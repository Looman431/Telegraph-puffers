<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;

class Handler extends WebhookHandler
{

    public function start(): void{
        $this->chat->message('Старт')->send();
    }

    public function action(): void{
        $this->chat->message('Полезные ссылки')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('👾 Наш дискорд')->url('https://discord.gg/RAVKJ3eE'),
                    Button::make('🎫 Приглашение в телеграм')->url('https://t.me/+GdBTXPtxdh40YmUy'),
                    Button::make('Написать админу группы')->action('Admin'),
                ])
            )->send();
    }

    public function Admin(): void{
        $this->chat->storage()->set('awaiting_admin_message', true);

        $this->chat->message('Ваше сообщение для админа:')->send();
    }
}
