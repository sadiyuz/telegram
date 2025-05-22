<?php

/**
 * The person who wrote this class: t.me/sadiyuz
 * 
 * If you also need the perfect Telegram bot, get in touch!
 */

namespace sadiyuz;

use sadiyuz\Logger;
use GuzzleHttp\Client;


class Telegram
{
    protected string $bot_token;
    private $logger;
    protected $client;

    public function __construct(string $bot_token, Logger $logger)
    {
        $this->bot_token = $bot_token;
        $this->logger = $logger;

        $this->client = new Client([
            'base_uri' => "https://api.telegram.org/bot{$this->bot_token}/"
        ]);
    }

    public function request(string $method, array $params = [], string $httpMethod = 'POST')
    {
        $options = [];

        if ($httpMethod === 'GET') {
            $options['query'] = $params;
        } else {
            $options['json'] = $params;
        }

        try {
            $response = $this->client->request($httpMethod, $method, $options);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }
}
