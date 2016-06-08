<?php

namespace JK\Sam\Tests\File;

use JK\Sam\Tests\PHPUnitBase;

class TaskTest extends PHPUnitBase
{
    public function testSetSources()
    {
        $task = $this->getTask('test', [
            'sources' => [
                'source.css'
            ],
            'destinations' => [
                'destinations.css'
            ],
            'filters' => []
            ,
            'debug' => true
        ]);
        $task->setSources([
            'new.source.css'
        ]);

        $this->assertContains('new.source.css', $task->getSources());
        $this->assertNotContains('source.css', $task->getSources());
        $this->assertEquals(true, $task->isDebug());
        $this->assertEquals('test', $task->getName());
    }
}
