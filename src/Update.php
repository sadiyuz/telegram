<?php

namespace sadiyuz;

class Update
{
    private $telegram;
    private $offset = 0;
    private $updates = [];


    public function __construct(Telegram $telegram)
    {
        $this->telegram = $bot_token;
    }

    public function getUpdatesPolling(int $timeout = 10): array
    {

        $params = [
            'offset' => $this->offset + 1,
            'timeout' => $timeout,
        ];

        $result = $this->telegram->request('getUpdates', $params);

        $this->updates = $result['result'];

        if (count($this->updates) > 0) {
            $lastUpdateId = end($this->updates)['update_id'];
            $this->offset = $lastUpdateId;
        }

        return $this->updates;
    }

    public function getUpdateWebhook(): ?array
    {
        $raw = file_get_contents("php://input");

        if (!$raw) {
            return null;
        }

        $update = json_decode($raw, true);

        if (!$update) {
            return null;
        }

        return $update;
    }
}
