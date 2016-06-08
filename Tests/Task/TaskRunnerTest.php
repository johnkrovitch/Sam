<?php

namespace JK\Sam\Tests\File;

use JK\Sam\Filter\Copy\CopyFilter;
use JK\Sam\Tests\PHPUnitBase;

class TaskRunnerTest extends PHPUnitBase
{
    public function testRun()
    {
        // the task runner MUST fail running a task with a non configured filter
        $task = $this->getTask('empty_task', [
            'sources' => [],
            'destinations' => [],
            'filters' => [
                'wrong'
            ],
        ]);
        $taskRunner = $this->getTaskRunner();
        $this->assertExceptionThrown(function() use ($task, $taskRunner) {
            $taskRunner->run($task);
        }, 'Invalid filter wrong. Check your mapping configuration');
        // ***********************************************

        // the task runner MUST fail running a task with a bad filter
        $task = $this->getTask('copy_task', [
            'sources' => [],
            'destinations' => [],
            'filters' => [
                'copy'
            ],
        ]);
        $copyFilter = $this
            ->getMockBuilder(CopyFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $filters['copy'] = $copyFilter;
        $taskRunner = $this->getTaskRunner($filters);
        $this->assertExceptionThrown(function() use ($task, $taskRunner) {
            $taskRunner->run($task);
        }, 'No supported extensions found for the filter ');
        // ***********************************************

        // the task runner MUST run with success with a right empty task
        $task = $this->getTask('copy_task', [
            'sources' => [],
            'destinations' => [],
            'filters' => [
                'copy'
            ],
        ]);
        $copyFilter = $this
            ->getMockBuilder(CopyFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $copyFilter
            ->method('getSupportedExtensions')
            ->willReturn([
                'css'
            ]);
        $filters['copy'] = $copyFilter;
        $taskRunner = $this->getTaskRunner($filters);
        $taskRunner->run($task);
        // ***********************************************

        // the task runner MUST run with success with a right task
        touch($this->getCacheDir().'/copy.css');
        touch($this->getCacheDir().'/other.copy.css');
        touch($this->getCacheDir().'/do.not.use.js');
        $task = $this->getTask('copy_task', [
            'sources' => [
                $this->getCacheDir().'/copy.css',
                $this->getCacheDir().'/other.copy.css',
                $this->getCacheDir().'/do.not.use.js',
            ],
            'destinations' => [
                $this->getCacheDir().'/copied.css'
            ],
            'filters' => [
                'copy'
            ],
        ]);
        $copyFilter = $this
            ->getMockBuilder(CopyFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $copyFilter
            ->method('getSupportedExtensions')
            ->willReturn([
                'css'
            ]);
        $copyFilter
            ->method('run')
            ->willReturn([$this->getCacheDir().'/do.not.use.js']);
        $filters['copy'] = $copyFilter;
        $filters['copy2'] = $copyFilter;
        $taskRunner = $this->getTaskRunner($filters);
        $taskRunner->run($task);
        // ***********************************************

        // the task runner MUST fail with bad updated sources
        $task = $this->getTask('copy_task', [
            'sources' => [
                $this->getCacheDir().'/*.css',
            ],
            'destinations' => [
                $this->getCacheDir().'/copied.css'
            ],
            'filters' => [
                'copy',
                'copy2',
            ],
        ]);
        $copyFilter = $this
            ->getMockBuilder(CopyFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $copyFilter
            ->method('getSupportedExtensions')
            ->willReturn([
                '*'
            ]);
        $filters['copy'] = $copyFilter;
        $filters['copy2'] = $copyFilter;
        $taskRunner = $this->getTaskRunner($filters);
        $taskRunner->run($task);

        $task = $this->getTask('copy_task', [
            'sources' => [
                $this->getCacheDir().'/copy.css',
                $this->getCacheDir().'/other.copy.css',
                $this->getCacheDir().'/do.not.use.js',
            ],
            'destinations' => [
                $this->getCacheDir().'/copied.css'
            ],
            'filters' => [
                'copy'
            ],
        ]);
        $copyFilter = $this
            ->getMockBuilder(CopyFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $copyFilter
            ->method('getSupportedExtensions')
            ->willReturn([
                'css'
            ]);
        $copyFilter
            ->method('run')
            ->willReturn([42]);
        $filters['copy'] = $copyFilter;
        $filters['copy2'] = $copyFilter;
        $taskRunner = $this->getTaskRunner($filters);
        $this->assertExceptionThrown(function() use ($task, $taskRunner) {
            $taskRunner->run($task);
        }, 'Invalid source file type integer');
    }
}
