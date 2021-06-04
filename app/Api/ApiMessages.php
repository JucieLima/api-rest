<?php


namespace App\Api;


class ApiMessages
{
    private $message = [];

    /**
     * ApiMessages constructor.
     * @param string $message
     * @param array $data
     */
    public function __construct(string $message, array $data = [], string $file = '', string $line = '')
    {
        $this->message['message'] = $message;
        $this->message['errors'] = $data;
        $this->message['file'] = $file;
        $this->message['line'] = $line;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }


}
