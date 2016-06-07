<?php

namespace JK\Sam\Event;

use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    const NAME = 'jk.assets.notification';

    /**
     * @var string
     */
    protected $message;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
