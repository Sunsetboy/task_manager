<?php


use Laravel\Lumen\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function create_a_task_by_existing_user()
    {
        $user = factory('App\User')->create();

        $this->json('PUT', '/task', [
            'title' => 'Start make pushes every day',
            'user_id' => $user->id,
            'datetime' => '2020-02-01 10:00:00',
        ])
            ->seeJson([
                'title' => 'Start make pushes every day',
            ]);

        $this->seeInDatabase('task', ['title' => 'Start make pushes every day']);
    }

    /**
     * @test
     */
    public function try_create_a_task_for_not_existing_user()
    {
        $this->json('PUT', '/task', [
            'title' => 'Start make pushes every day',
            'user_id' => 10000,
            'datetime' => '2020-02-01 10:00:00',
        ])
            ->seeStatusCode(400);
    }

    /**
     * @test
     */
    public function get_an_existing_task()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create(['user_id' => $user->id]);

        $this->json('GET', '/task/' . $task->id)
            ->seeJson([
                'title' => $task->title,
            ]);
    }

    /**
     * @test
     */
    public function get_a_non_existing_task()
    {
        $this->json('GET', '/task/100023')
            ->seeStatusCode(404);
    }
}
