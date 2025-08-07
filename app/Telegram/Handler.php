<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;

class Handler extends WebhookHandler
{

    public function test(): void{
        $this->chat->message('test')->send();
    }

    public function action(): void{
        $this->chat->message('Полезные ссылки')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('⛓️ Полезные ссылки')->action('links'),
                    Button::make('🪪 Действия')->action('actions'),
                    Button::make('🤖 О боте')->url('https://github.com/Looman431/Telegraph-puffers'),
                ])
            )->send();
    }

    public function links(): void{
        $this->chat->message('⛓️ Полезные ссылки')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('👾 Наш дискорд')->url('https://discord.gg/RAVKJ3eE'),
                    Button::make('🎫 Приглашение в телеграм')->url('https://t.me/+GdBTXPtxdh40YmUy'),
                    Button::make('🧿 Наш чат в MAX')->action('nahuy'),
                ])
            )->send();
    }

    public function actions(): void{
        $this->chat->message('Пока что не работает :(')
        ->keyboard(
            Keyboard::make()->buttons([
                Button::make('Написать админу')->action('Admin'),
                Button::make('---')->action('first'),
                Button::make('---')->action('second'),
            ])
        )->send();
    }

    public function nahuy(): void{
        $this->chat->message('Иди нахуй')->send();
    }

    public function first(): void{
        $this->chat->message('---')->send();
    }

    public function second(): void{
        $this->chat->message('---')->send();
    }



    //Не рабочий кусок
    public function Admin(): void
    {
        // Сохраняем "состояние", что пользователь хочет написать админу
        Log::info('[BOT] Нажата кнопка "Написать админу"');

        $this->chat->storage()->set('awaiting_admin_message', true);

    }

    public function text(string $text): void
    {
        // Проверяем, ожидаем ли сообщение для админа
        if ($this->chat->storage()->get('awaiting_admin_message')) {
            $this->chat->storage()->forget('awaiting_admin_message'); // Сброс флага

            $adminChatId = config('telegram.admin_chat_id'); // <-- лучше в .env

            Telegraph::chat($adminChatId)->message("Новое сообщение от пользователя @{$this->chat->chat_id}:\n\n$text")->send();

            $this->chat->message('Ваше сообщение отправлено админу.')->send();
            return;
        }

        // Иначе просто обычное сообщение
        Telegraph::message("Вы написали: $this->chat->message")->send();
    }

}
