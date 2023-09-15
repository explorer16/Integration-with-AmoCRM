<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadioButtonCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Collections\CustomFieldsValuesCollection;

class createContact extends Model
{
    use HasFactory;

    /**
     * @param array
     * @param AmoCRM\Models\ContactModel
     * @return \AmoCRM\Collections\CustomFieldsValuesCollection
     */

    static function create($contactData, $contact)
    {
//Добавим имя и фамилию
        $contact->setName($contactData['first_name'] . ' ' . $contactData['last_name']);
        $contact->setFirstName($contactData['first_name']);
        $contact->setLastName($contactData['last_name']);

        $customFields = new CustomFieldsValuesCollection();
//Добавим контакт
        $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
        
        $customFields->add($phoneField);
        $phoneField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setEnum('WORKDD')
                        ->setValue($contactData['telephone'])
                )
        );
//Добавим Email
        $emailField = (new MultitextCustomFieldValuesModel())->setFieldCode('EMAIL');
                    
        $customFields->add($emailField);
        $emailField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setEnum('WORK')
                        ->setValue($contactData['email'])
                )
        );
//Добавим возраст
        $ageField = (new NumericCustomFieldValuesModel())->setFieldId(1545569);
                    
        $customFields->add($ageField);
        $ageField->setValues(
            (new NumericCustomFieldValueCollection())
                ->add(
                    (new NumericCustomFieldValueModel())
                        ->setValue(intval($contactData['age']))
                )
        );
// Добавим пол
        $genderField = (new RadiobuttonCustomFieldValuesModel())->setFieldId(1545573);
                
        $customFields->add($genderField);
        $genderField->setValues(
            (new RadiobuttonCustomFieldValueCollection())
                ->add(
                    (new RadioButtonCustomFieldValueModel())
                        ->setValue($contactData['gender'])
                )
        );
        return $customFields;
    }
}
