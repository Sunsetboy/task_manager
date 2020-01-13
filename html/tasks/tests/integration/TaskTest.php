<?php

namespace Test\integration;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Test\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function create_a_task_by_existing_user()
    {
        $user = factory('App\User')->create();

        $this->put('/task', [
            'title' => 'Start make pushes every day',
            'user_id' => $user->id,
            'datetime' => '2020-02-01 10:00:00',
        ])->seeJson([
            'title' => 'Start make pushes every day',
        ]);

        $this->seeInDatabase('task', ['title' => 'Start make pushes every day']);
    }

    /**
     * @test
     */
    public function try_create_a_task_for_not_existing_user()
    {
        $this->put('/task', [
            'title' => 'Start make pushes every day',
            'user_id' => 10000,
            'datetime' => '2020-02-01 10:00:00',
        ])->seeStatusCode(400);
    }

    /**
     * @test
     */
    public function get_an_existing_task()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create(['user_id' => $user->id]);

        $this->get('/task/' . $task->id)
            ->seeJson([
                'title' => $task->title,
            ]);
    }

    /**
     * @test
     */
    public function get_a_non_existing_task()
    {
        $this->get('/task/100023')
            ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function delete_a_non_existing_task()
    {
        $this->delete('/task/10023')
            ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function delete_an_existing_task()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create(['user_id' => $user->id]);

        $this->seeInDatabase('task', [
            'id' => $task->id,
            'is_deleted' => 0,
        ]);

        $this->delete('/task/' . $task->id)
            ->seeStatusCode(200);

        $this->seeInDatabase('task', [
            'id' => $task->id,
            'is_deleted' => 1,
        ]);
    }

    /**
     * @test
     */
    public function get_tasks_of_non_existing_user()
    {
        $this->get('/task/user/1000')
            ->seeStatusCode(400);
    }

    /**
     * @test
     */
    public function get_tasks_of_user()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create([
            'user_id' => $user->id,
            'title' => 'find a job',
        ]);

        $this->get('/task/user/' . $user->id)
            ->seeJson([
                'title' => 'find a job',
                'id' => $task->id,
            ]);
    }

    /**
     * @test
     */
    public function try_to_get_a_deleted_task()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create([
            'user_id' => $user->id,
            'title' => 'deleted task',
            'is_deleted' => 1,
        ]);

        $this->get('/task/user/' . $user->id)
            ->dontSeeJson([
                'id' => $task->id,
            ]);
    }

    /**
     * @test
     */
    public function get_tasks_by_filter()
    {
        $user = factory('App\User')->create();

        $todayTask = factory('App\Task')->create([
            'user_id' => $user->id,
            'title' => 'today task',
            'is_deleted' => 0,
            'datetime' => '2020-01-10 10:00:05',
        ]);
        $yesterdayTask = factory('App\Task')->create([
            'user_id' => $user->id,
            'title' => 'yesterday task',
            'is_deleted' => 0,
            'datetime' => '2020-01-09 16:00:00',
            'status' => 'complete',
        ]);
        $deletedTodayTask = factory('App\Task')->create([
            'user_id' => $user->id,
            'title' => 'today task deleted',
            'is_deleted' => 1,
            'datetime' => '2020-01-10 16:00:00',
        ]);

        $this->get('/task/user/' . $user->id . '?date=2020-01-10')
            ->seeJson([
                'title' => 'today task',
                'id' => $todayTask->id,
            ])->dontSeeJson([
                'title' => 'today task deleted',
                'id' => $deletedTodayTask->id,
            ])->dontSeeJson([
                'title' => 'yesterday task',
            ]);

        $this->get('/task/user/' . $user->id . '?status=complete')
            ->seeJson([
                'id' => $yesterdayTask->id,
            ]);

        $this->get('/task/user/' . $user->id . '?date=2000-01-10')
            ->seeJsonDoesntContains(['id']);
    }

    /**
     * @test
     */
    public function try_to_update_non_existing_task()
    {
        $this->post('/task/1000', ['title' => 'new title'])
            ->seeStatusCode(404);
    }

    /**
     * @test
     * attribute is_deleted could not be updated via update request
     */
    public function update_a_task()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create([
            'user_id' => $user->id,
            'title' => 'old task',
        ]);

        $this->post('/task/' . $task->id, [
            'title' => 'new title',
            'is_deleted' => 1,
        ])->seeJson(['title' => 'new title']);

        $this->seeInDatabase('task', [
            'id' => $task->id,
            'title' => 'new title',
            'is_deleted' => 0,
        ]);
    }
}
