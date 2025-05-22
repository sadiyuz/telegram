<?php

namespace sadiyuz;

class Logger
{
    private $logFile;

    public function __construct($filePath = 'app.log')
    {
        $this->logFile = $filePath;
    }

    public function log($message)
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] " . $message . PHP_EOL;
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }
}
