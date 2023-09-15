<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chooseRandomUser extends Model
{
    use HasFactory;

    /**
     * @param \AmoCRM\EntitiesServices\Users
     */
    static function choose($userService)
    {
        $users = $userService->get();
        $userIds = [];
        foreach($users as $user){
            $userIds[] = $user->getId();
        }
         return $userIds[rand()%count($userIds)];
    }
}
