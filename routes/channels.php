<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('match.{matchId}', function ($user, $matchId) {
    return true;
});