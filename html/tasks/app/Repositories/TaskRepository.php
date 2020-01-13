<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Collection;

/**
 * Encapsulates complex access to tasks stored in DB
 * Class TaskRepository
 * @package App\Repositories
 */
class TaskRepository
{
    /**
     * @param User $user
     * @param array $params
     * @return Collection
     * @todo order results
     */
    public function getTasksList(User $user, $params = []): Collection
    {
        $userQueryBuilder = $user->tasks()->where('is_deleted', 0);

        if (isset($params['date'])) {
            $userQueryBuilder = $userQueryBuilder->whereDate('datetime', $params['date']);
        }

        if (isset($params['status'])) {
            $userQueryBuilder = $userQueryBuilder->where('status', $params['status']);
        }

        return $userQueryBuilder->get();
    }
}
