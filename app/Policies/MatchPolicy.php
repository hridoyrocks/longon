<?php
// app/Policies/MatchPolicy.php
namespace App\Policies;

use App\Models\Match;
use App\Models\User;

class MatchPolicy
{
    public function view(User $user, Match $match)
    {
        return $user->id === $match->user_id || $user->isAdmin();
    }

    public function update(User $user, Match $match)
    {
        return $user->id === $match->user_id || $user->isAdmin();
    }

    public function delete(User $user, Match $match)
    {
        return $user->id === $match->user_id || $user->isAdmin();
    }
}