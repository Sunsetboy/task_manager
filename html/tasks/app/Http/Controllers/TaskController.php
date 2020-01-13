<?php


namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Task;
use App\Town;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        // @todo add filtering by date, status, etc.
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('Invalid user id', 400);
        }

        $tasksRepository = new TaskRepository();
        $tasks = $tasksRepository->getTasksList($user, $request->all());

        return $tasks;
    }

    /**
     * Returns a single task info
     * @param Request $request
     * @param integer $id
     * @return Collection|JsonResponse
     */
    public function get(Request $request, $id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return response()->json('Task not found', 404);
        }

        return $task;
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
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return response()->json('Task not found', 404);
        }

        $attributes = $this->validate($request, [
            'title' => ['max:255'],
            'priority' => ['integer'],
            'description' => ['max:1000'],
            'datetime' => ['date'],
            'status' => [Rule::in(['complete', 'incomplete'])],
        ]);

        $task->fill($attributes)->save();

        return $task;
    }

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return response()->json('Task not found', 404);
        }

        $task->is_deleted = 1;
        $task->save();

        return response()->json('Task is deleted', 200);
    }
}
