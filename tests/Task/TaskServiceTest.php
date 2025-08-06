<?php

namespace Tests\Task;

use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Shared\HttpServer\Lib\Test\CoroutineTest;
use App\Task\Application\Service\TaskService;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;
use Swoole\Http\Response;

class TaskServiceTest extends TestCase
{
    use CoroutineTest;

    public function test_create_task()
    {
        /**
         * @var TaskService $service
         */
        $service = APP->get(TaskService::class);
        $request = new Request();

        $result = $service->createTask($request);
        $this->assertEquals(HttpStatus::BAD_REQUEST, $result['code']);

        $this->runTestOnCoroutine(function () use ($service, $request) {
            $request->post = [
                'title' => "foo",
                'description' => "bar",
            ];
            $result = $service->createTask($request);
            $this->assertEquals(HttpStatus::CREATED, $result['code']);
            $task = $result['data'];
            $this->assertIsInt($task['id']);
            $this->assertEquals('foo', $task['title']);
            $this->assertEquals('bar', $task['description']);
            $this->assertNotTrue($task['isDone']);
            $this->assertIsInt($task['createdAt']);
        });
    }

}
