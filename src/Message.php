<?php

namespace sadiyuz;

use sadiyuz\Telegram;
use sadiyuz\Update;

class Message {
    private $telegram;
    private $update;

    public function __construct(Telegram $telegram, Update $update)
    {
        $this->telegram = $telegram;
        $this->update = $update;
    }

    public function sendMessage($chatId, $text, $mode = 'html', $msgId = null, $keyboard = null)
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $mode,
            'disable_web_page_preview' => true,
            'reply_to_message_id' => $msgId,
            'reply_markup' => $keyboard
        ];
        $params = array_filter($params, function ($value) {
            return !is_null($value);
        });
        $data = $this->telegram->request('sendmessage', $params);
        return $data;
    }

    public function sendPhoto($chatId, $photo, $caption = '', $mode = 'html', $keyboard = null, $opt = [])
    {
        $params = [
            'chat_id' => $chatId,
            'photo' => $photo,
            'caption' => $caption,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ];
        $data = array_merge($params, $opt);
        $response = $this->telegram->request('sendPhoto', $data);
        return $response;
    }

    public function sendVideo($chatId, $video, $caption = '', $mode = 'html', $keyboard = null, $opt = [])
    {
        $params = [
            'chat_id' => $chatId,
            'video' => $video,
            'caption' => $caption,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ];
        $data = array_merge($params, $opt);
        $response = $this->telegram->request('sendVideo', $data);
        return $response;
    }

    public function sendDocument($chatId, $document, $caption = '', $mode = 'html', $keyboard = null, $opt = [])
    {
        $params = [
            'chat_id' => $chatId,
            'document' => $document,
            'caption' => $caption,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ];
        $data = array_merge($params, $opt);
        $response = $this->telegram->request('sendPhoto', $data);
        return $response;
    }

    public function editMessageText($chatId, $text, $mode, $msgId, $keyboard = null, $opt = [])
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $mode,
            'message_id' => $msgId,
            'reply_markup' => $keyboard
        ];
        $data = array_merge($params, $opt);
        $response = $this->telegram->request('editMessageText', $data);
        return $response;
    }

    public function editMessageCaption($chatId, $caption, $mode, $msgId, $keyboard = null, $opt = [])
    {
        $params = [
            'chat_id' => $chatId,
            'caption' => $caption,
            'parse_mode' => $mode,
            'message_id' => $msgId,
            'reply_markup' => $keyboard
        ];
        $data = array_merge($params, $opt);
        $response = $this->telegram->request('editMessageCaption', $data);
        return $response;
    }

    public function editMessageReplyMarkup($chatId, $msgId, $keyboard = null)
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $msgId,
            'reply_markup' => $keyboard
        ];
        $response = $this->telegram->request('editMessageReplyMarkup', $params);
        return $response;
    }

    public function deleteMessage($chatId, $msgId)
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $msgId
        ];
        $response = $this->telegram->request('deleteMessage', $params);
        return $response;
    }

    public function reply($text, $keyboard = null)
    {
        $update = $this->update->getUpdates();
        $update = $update[count($update) - 1];
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
        $msgId = $update['message']['message_id'] ?? $update['callback_query']['message']['message_id'];

        return $this->sendMessage($chatId, $text, 'html', $msgId, $keyboard);
    }

    public function replyWithPhoto($photo, $caption = null, $keyboard = null)
    {
        $update = $this->update->getUpdates();
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
        $msgId = $update['message']['message_id'] ?? $update['callback_query']['message']['message_id'];

        $params = ['reply_to_message_id' => $msgId];
        return $this->sendPhoto($chatId, $photo, $caption, 'html', $keyboard, $params);
    }

    public function replyWithVideo($video, $caption = null, $keyboard = null)
    {
        $update = $this->update->getUpdates();
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
        $msgId = $update['message']['message_id'] ?? $update['callback_query']['message']['message_id'];

        $params = ['reply_to_message_id' => $msgId];
        return $this->sendVideo($chatId, $video, $caption, 'html', $keyboard, $params);
    }

    public function replyWithDocument($document, $caption = null, $keyboard = null)
    {
        $update = $this->update->getUpdates();
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
        $msgId = $update['message']['message_id'] ?? $update['callback_query']['message']['message_id'];

        $params = ['reply_to_message_id' => $msgId];
        return $this->sendDocument($chatId, $document, $caption, 'html', $keyboard, $params);
    }


    public function editReplyMessageText($text, $keyboard = null)
    {
        $update = $this->update->getUpdates();
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
        $msgId = $update['message']['message_id'] ?? $update['callback_query']['message']['message_id'];

        $params = ['reply_to_message_id' => $msgId];
        return $this->editMessageText($chatId, $text, 'html', $msgId, $keyboard);
    }
}