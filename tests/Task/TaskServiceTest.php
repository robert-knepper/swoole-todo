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
            $this->assertEquals(HttpStatus::BAD_REQUEST, $result->getCode());

            $request->post = [
                'title' => "foo",
                'description' => "bar",
            ];
            $result = $service->createTask($request);
            $task = $result->getData();

            // check task returned
            $this->assertEquals(HttpStatus::CREATED, $result->getCode());
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
            $task = $result->getData();

            // get task
            $result = $service->getTask($request);
            $this->assertEquals(HttpStatus::BAD_REQUEST, $result->getCode());
            $request->get = [
                'id' => $task['id'],
            ];
            $result = $service->getTask($request);
            $this->assertEquals(HttpStatus::OK, $result->getCode());
            $this->assertEquals('foo', $result->getData()['title']);
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
            $this->assertEquals(HttpStatus::OK, $result->getCode());

            $this->assertTrue(count($result->getData()) > 2);
            /**
             * @var Task[] $tasks
             */
            $tasks = $result->getData();
            $this->assertEquals('foo', $tasks[0]->title);
        });
    }

    public function test_delete_task()
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
            $result = $service->createTask($request);
            $task = $result->getData();

            $request->get = [
                'id' => $task['id'],
            ];
            $result = $service->getTask($request);
            $this->assertEquals(HttpStatus::OK, $result->getCode());

            $request->post = [
                'id' => $task['id'],
            ];
            $result = $service->delete($request);
            $this->assertEquals(HttpStatus::OK, $result->getCode());

            $request->get = [
                'id' => $task['id'],
            ];
            $result = $service->getTask($request);
            $this->assertEquals(HttpStatus::NOT_FOUND, $result->getCode());
        });
    }



    public function test_update_task()
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
            $result = $service->createTask($request);
            $task = $result->getData();
            $task['title'] = "baz";
            $task['description'] = "qux";
            $task['isDone'] = 1;

            $request->post = $task;
            $result = $service->update($request);
            $this->assertEquals(HttpStatus::OK, $result->getCode());
            $this->assertEquals('baz', $result->getData()['title']);

            $request->get = [
                'id' => $task['id'],
            ];
            $result = $service->getTask($request);


            $this->assertEquals(HttpStatus::OK, $result->getCode());
            $this->assertEquals('baz', $result->getData()['title']);
            $this->assertEquals('qux', $result->getData()['description']);
            $this->assertTrue($result->getData()['isDone']);
        });
    }

    public function test_update_is_done_task()
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
            $result = $service->createTask($request);
            $task = $result->getData();
            $task['isDone'] = 1;

            $request->post = $task;
            $result = $service->updateIsDone($request);
            $this->assertEquals(HttpStatus::OK, $result->getCode());
            $this->assertEquals(1, $result->getData()['isDone']);
        });
    }
}
