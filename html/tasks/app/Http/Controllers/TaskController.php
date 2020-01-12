<?php


namespace App\Http\Controllers;

use App\Task;
use App\Town;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Controller for tasks management
 * Class TaskController
 * @package App\Http\Controllers
 */
class TaskController extends Controller
{
    /**
     * Returns a list of tasks for a user
     * @param Request $request
     * @param integer $id
     * @return Collection|JsonResponse
     */
    public function getListForUser(Request $request, $id)
    {

    }

    /**
     * Returns a single task info
     * @param Request $request
     * @param integer $id
     * @return Collection|JsonResponse
     */
    public function get(Request $request, $id)
    {

    }

    /**
     * @param Request $request
     * @return Task|JsonResponse
     */
    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'title' => ['required', 'max:255'],
            'priority' => ['integer'],
            'description' => ['max:1000'],
            'user_id' => ['required', 'integer'],
            'datetime' => ['required', 'date'],
        ]);

        $user = User::find($request->input('user_id'));
        if (is_null($user)) {
            return response()->json('Invalid user', 400);
        }

        /** @var Task $task */
        $task = Task::create($attributes);

        return ($task->save()) ?
            $task :
            response()->json('Bad request data', 400);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return Task|JsonResponse
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function delete($id)
    {

    }
}
