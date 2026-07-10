<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\Utilisateur;

class NotificationPolicy
{
    public function update(Utilisateur $user, Notification $notification): bool
    {
        return $notification->id_utilisateur === $user->id;
    }
}
