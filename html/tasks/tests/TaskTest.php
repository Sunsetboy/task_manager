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
        $user = factory('App\User')->make();

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
}
