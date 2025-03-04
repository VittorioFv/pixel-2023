<?php

class Logger
{
    private $fileManager;

    /**
     *
     */
    public function __construct(App $app)
    {
        $this->fileManager = new FileManager($app);
    }

    /**
     * @return string
     */
    public static function getLogFilename(): string
    {
        return 'logs.txt';
    }

    /**
     * @param string $email
     * @return void
     */
    public function writeLogLogin(string $email)
    {
        $this->writeLog("LOGIN", [$email]);
    }

    /**
     * @param string $email
     * @return void
     */
    public function writeLogLogout(string $email)
    {
        $this->writeLog('LOGOUT', [$email]);
    }

    /**
     * @param string $email
     * @return void
     */
    public function writeLogRegistration(string $email)
    {
        $this->writeLog('REGISTRATION', [$email]);
    }

    /**
     * @param string $event
     * @param array $tags
     * @return void
     */
    private function writeLog(string $event, array $tags = [])
    {
        $logsFilename = self::getLogFilename();

        $this->fileManager->createFileIfNotExists($logsFilename);

        // costruisco il messaggio da scrivere
        $row = $this->formatLogMessage($event, $tags);

        // scrivo il messaggio di log
        file_put_contents(
            $this->fileManager->buildPathRelativeToProjectRoot($logsFilename),
            $row,
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * @param string $message
     * @param array $tags array piano di stringhe usate come tag
     * @return string
     */
    private function formatLogMessage(string $message, array $tags = []): string
    {
        $message = date('[Y-m-d H:i:s]') . ' ' . $message;

        if (count($tags) > 0) {
            $message .= ' [';
            $message  = $message . implode(', ', $tags);
            $message .= ']';
        }

        return $message . PHP_EOL;
    }
}