<?php

namespace App\Models;

use AmoCRM\Models\LeadModel;

class Is_set
{

    /**
     * метод проверяет наличие контакта на аккаунте
     * @param \AmoCRM\Collections\ContactsCollection
     * @param string
     */
    static function contact(\AmoCRM\Collections\ContactsCollection $contactCollection, $phone): ?int 
    {
        foreach($contactCollection as $contact){
            if($contact->getCustomFieldsValues() !== null){
                $telephone = $contact->getCustomFieldsValues()[0]->values[0]->value;
                if($telephone === $phone){
                    session(['contactId' => $contact->getId()]);
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * @param \AmoCRM\Collections\Leads\LeadsCollection
     * @param int
     * @return bool
     */
    static function unCompletedLead(\AmoCRM\Collections\Leads\LeadsCollection $leadCollection, $contactId)
    {
        foreach($leadCollection as $lead){
            if($lead->getContacts()!=null){
                if($lead->getContacts()[0]->getId() === $contactId && $lead->getStatusId() !== LeadModel::WON_STATUS_ID) {
                    session(['leadId' => $lead->getId()]);
                    return true;
                }
            }  
        }
        return false;
    }

}
