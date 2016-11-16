<?php

namespace JK\Sam\Tests\Notification;

use JK\Sam\Event\NotificationEvent;
use JK\Sam\Tests\PHPUnitBase;

class NotificationEventTest extends PHPUnitBase
{
    public function testEvent()
    {
        $event = new NotificationEvent();
        $event->setMessage('my message');
        $this->assertEquals('my message', $event->getMessage());
    }
}
