<?php


namespace App\Http\Controllers;

use App\Task;
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
