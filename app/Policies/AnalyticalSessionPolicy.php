<?php

namespace App\Policies;

use App\Models\AnalyticalSession;
use App\Models\User;

class AnalyticalSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AnalyticalSession $session): bool
    {
        $role = $user->role?->name;

        if ($role === 'analyst') {
            return (int) $session->user_id === (int) $user->id;
        }

        // leader/manager могут смотреть (без административных действий)
        return in_array($role, ['admin', 'leader', 'manager'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'analyst'], true);
    }

    public function rerun(User $user, AnalyticalSession $session): bool
    {
        $role = $user->role?->name;

        if ($role === 'analyst') {
            return (int) $session->user_id === (int) $user->id;
        }

        return $role === 'admin';
    }

    public function delete(User $user, AnalyticalSession $session): bool
    {
        $role = $user->role?->name;

        if ($role === 'analyst') {
            return (int) $session->user_id === (int) $user->id;
        }

        return $role === 'admin';
    }

    public function export(User $user, AnalyticalSession $session): bool
    {
        $role = $user->role?->name;

        if ($role === 'analyst') {
            return (int) $session->user_id === (int) $user->id;
        }

        return in_array($role, ['admin', 'leader'], true);
    }
}

