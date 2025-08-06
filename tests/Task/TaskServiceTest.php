<?php

namespace Tests\Task;

use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Shared\HttpServer\Lib\Test\CoroutineTest;
use App\Task\Application\Service\TaskService;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;

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

        // check validation
        $this->assertEquals(HttpStatus::BAD_REQUEST, $result['code']);

        $this->runTestOnCoroutine(function () use ($service, $request) {
            $request->post = [
                'title' => "foo",
                'description' => "bar",
            ];
            $result = $service->createTask($request);
            $task = $result['data'];

            // check task returned
            $this->assertEquals(HttpStatus::CREATED, $result['code']);
            $this->assertIsInt($task['id']);
            $this->assertEquals('foo', $task['title']);
            $this->assertEquals('bar', $task['description']);
            $this->assertNotTrue($task['isDone']);
            $this->assertIsInt($task['createdAt']);
        });
    }

    public function test_get_task()
    {
        $this->runTestOnCoroutine(function () {
            /**
             * @var TaskService $service
             */
            $service = APP->get(TaskService::class);
            $request = new Request();
            $request->post = [
                'title' => "foo",
                'description' => "bar",
            ];

            // create task
            $result = $service->createTask($request);
            $task = $result['data'];

            // get task
            $result = $service->getTask($request);
            $this->assertEquals(HttpStatus::BAD_REQUEST, $result['code']);
            $request->get = [
                'id' => $task['id'],
            ];
            $result = $service->getTask($request);
            $this->assertEquals(HttpStatus::OK, $result['code']);
            $this->assertEquals('foo', $result['data']['title']);
        });
    }
}
