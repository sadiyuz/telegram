<?php

/**
 * The person who wrote this class: t.me/sadiyuz
 * 
 * If you also need the perfect Telegram bot, get in touch!
 */

namespace sadiyuz;

use sadiyuz\Logger\Logger;

class Telegram
{
    protected string $bot_token;
    private $logger;
    public const TELEGRAM_BOT_API = "https://api.telegram.org/bot";

    public function __construct(string $bot_token, Logger $logger)
    {
        $this->bot_token = $bot_token;
        $this->logger = $logger;
    }

    public function request($method, $params = [])
    {
        try {
            $ch = curl_init();
            if ($ch === false) {
                throw new \Exception('cURL init failed');
            }

            curl_setopt($ch, CURLOPT_URL, self::TELEGRAM_BOT_API . $this->bot_token . "/" . $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                throw new \Exception("cURL error: " . $error);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new \Exception("Telegram API returned HTTP code " . $httpCode . ". Response: " . $response);
            }

            $decoded = json_decode($response, true);

            if ($decoded === null) {
                throw new \Exception("Failed to decode JSON response: " . $response);
            }

            return $decoded;
        } catch (\Exception $e) {
            $this->logger->log("Telegram API request error: " . $e->getMessage());
            return false;
        }
    }
}
