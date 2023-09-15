<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\TasksCollection;
use AmoCRM\Models\TaskModel;
use AmoCRM\Helpers\EntityTypesInterface;

use AmoCRM\Models\LeadModel;

class makeCommonActionsAndReturnLead extends Model
{
    use HasFactory;

    static function do($apiClient)
    {
        //Создаём сделку и отправляем её в аккаунт
        $lead = new LeadModel();
        $lead->setName('Покупка');
        try{
            $lead = $apiClient->leads()->addOne($lead);
        } catch (AmoCRMApiException $e) {
            dd($e);
        }
        

//Берём 2 товара и вычисляем их общую стоимость
        $price = 0;
        $products = getProduct::get($apiClient, $price);

//Берём нашу сделку из аккаунта, добавляем к ней товары, цену
        $lead->setPrice($price);

        $link = new LinksCollection();
        foreach($products as $item) {
            $link->add($item);
        }
        
        try {
            $linkModel = $apiClient->leads()->link($lead, $link);
        } catch (AmoCRMApiException $e) {
            dd($e);
        }
        

        
//Выбираем случайного пользователя
        $userService = $apiClient->users();
        $userId = chooseRandomUser::choose($userService);

//Создаём задачу
        $tasksCollection = new TasksCollection();
        $task = new TaskModel();

        $time = setDueDate::set();
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_FOLLOW_UP)
            ->setText('Подготовить отчёт о проведении сделки')
            ->setCompleteTill($time)
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setEntityId($lead->getId())
            ->setDuration(30 * 60 * 60) //30 минут
            ->setResponsibleUserId($userId);
        $tasksCollection->add($task);
        $lead->setClosestTaskAt($time);

        try{
            $apiClient->tasks()->addOne($task);
        } catch (AmoCRMApiException $e) {
            dd($e);
        }

        return $lead;
    }
}
