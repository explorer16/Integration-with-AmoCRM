<?php

namespace App\Models;

class ChooseRandomUser
{
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
