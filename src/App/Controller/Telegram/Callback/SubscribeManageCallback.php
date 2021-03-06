<?php

namespace App\Controller\Telegram\Callback;

use Obokaman\StockForecast\Domain\Model\Subscriber\ChatId;
use Obokaman\StockForecast\Domain\Model\Subscriber\SubscriberExistsException;
use Obokaman\StockForecast\Domain\Model\Subscriber\SubscriberRepository;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class SubscribeManageCallback extends BaseCallback
{
    private $subscriber_repository;

    public function __construct(Client $a_telegram_client, SubscriberRepository $a_subscriber_repository)
    {
        parent::__construct($a_telegram_client);
        $this->subscriber_repository = $a_subscriber_repository;
    }

    public function getCallback(): string
    {
        return 'subscribe_manage';
    }

    public function execute(CallbackQuery $a_callback): void
    {
        $chat_id = $a_callback->getMessage()->getChat()->getId();
        $subscriber = $this->subscriber_repository->findByChatId(new ChatId($chat_id));
        if ($subscriber === null) {
            throw new SubscriberExistsException("It doesn't exist any user with chat id {$chat_id}");
        }
        if (empty($subscriber->subscriptions())) {
            return;
        }
        $buttons = [];
        foreach ($subscriber->subscriptions() as $subscription) {
            $buttons[] = [
                [
                    'text' => '❌ ' . $subscription->currency() . '-' . $subscription->stock(),
                    'callback_data' => json_encode(
                        [
                            'method' => 'subscribe_remove',
                            'currency' => (string)$subscription->currency(),
                            'crypto' => (string)$subscription->stock()
                        ]
                    )
                ]
            ];
        }
        $buttons[] = [
            [
                'text' => '« Cancel',
                'callback_data' => json_encode(
                    [
                        'method' => 'subscribe_cancel'
                    ]
                )
            ]
        ];
        $this->telegram_client->editMessageText(
            $chat_id,
            $a_callback->getMessage()->getMessageId(),
            'Ok, select what currency-crypto pair you want to stop receiving alerts from:',
            null,
            false,
            new InlineKeyboardMarkup($buttons)
        );
    }
}
