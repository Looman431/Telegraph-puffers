<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class Handler extends WebhookHandler
{

    public function test(): void{
        $this->chat->message('test')->send();
    }

    public function action(): void{
        $this->chat->message('?')
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
                Button::make('Написать в предложку')->action('Admin'),
                Button::make('Рандомное число')->action('first'),
                Button::make('---')->action('second'),
            ])
        )->send();
    }

    public function nahuy(): void{
        $this->chat->message('Иди нахуй')->send();
    }

    public function first(): void{
        $rand = mt_rand(1, 100);
        $this->chat->message('🎲' . $rand)->send();
    }

    public function second(): void{
        $this->chat->message('---')->send();
    }



    //Логика
    public function Admin(): void
    {
        $this->chat->storage()->set('awaiting_admin_message', true);

        $this->chat->message('Напишите свое сообщение')->send();
    }

    public function handleChatMessage(Stringable $text): void
    {
        if ($this->chat->storage()->get('awaiting_admin_message')) {
            $this->chat->storage()->forget('awaiting_admin_message');

            $AdminChatId = env('TELEGRAM_ADMIN_CHAT_ID');

            $userUsername = $this->message->from()->firstName();

            $UserName = $this->message->from()->username();

            $UserId = $this->message->from()->id();

            $MessageForAdmin = "Новое сообщение от пользователя:
            {$userUsername}
            @{$UserName}
             (ID:{$UserId})"
             . $text;

            Telegraph::chat($AdminChatId)->message($MessageForAdmin)->send();

            $this->chat->message('Ваше Сообщение отправлено')->send();
            return;
        } else

        Log::info(json_encode($this->message->toArray(), JSON_UNESCAPED_UNICODE));
    }
}
