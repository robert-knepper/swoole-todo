<?php

namespace Tests\Task;

use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Shared\HttpServer\Lib\Test\CoroutineTest;
use App\Task\Application\Service\TaskService;
use App\Task\Domain\Task;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;

class TaskServiceTest extends TestCase
{
    use CoroutineTest;

    public function test_create_task()
    {
        $this->runTestOnCoroutine(function () {
            /**
             * @var TaskService $service
             */
            $service = APP->get(TaskService::class);
            $request = new Request();

            $result = $service->createTask($request);

            // check validation
            $this->assertEquals(HttpStatus::BAD_REQUEST, $result['code']);

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

    public function test_get_all_task()
    {
        $this->runTestOnCoroutine(function () {
            /**
             * @var TaskService $service
             */
            $service = APP->get(TaskService::class);
            $request = new Request();


            // create task
            $request->post = [
                'title' => "foo",
                'description' => "bar",
            ];
            $service->createTask($request);
            $service->createTask($request);
            $service->createTask($request);


            // get task
            $result = $service->getAllTasks($request);
            $this->assertEquals(HttpStatus::OK, $result['code']);

            $this->assertTrue(count($result['data']) > 2);
            /**
             * @var Task[] $tasks
             */
            $tasks = $result['data'];
            $this->assertEquals('foo', $tasks[0]->title);
        });
    }
}
