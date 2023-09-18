<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Token;
use League\OAuth2\Client\Token\AccessTokenInterface;
use AmoCRM\Models\ContactModel;
use App\Models\createContact;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\NoteType\CommonNote;
use App\Models\is_set;
use App\Models\makeCommonActionsAndReturnLead;

class SendFormController extends Controller
{
    function send()
    {
        if (session('accessToken')) {
            $accessToken = Token::getToken();
        } else {
            return redirect('/getToken');
        }

        $clientId = config('amocrm.clientId');
        $clientSecret = config('amocrm.clientSecret');
        $redirectUri = config('amocrm.redirectUri');

        $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                }
            );

        $contactsCollection = $apiClient->contacts()->get();

        $contactData = json_decode(session('formData'), true);

        if (!is_set::contact($contactsCollection, $contactData['telephone'])) {
            $lead = makeCommonActionsAndReturnLead::do($apiClient);
            //Создаём контакт и заполняем его данными из формы
            $contact = new ContactModel();
            
            $customFields = createContact::create($contactData, $contact);
            $contact->setCustomFieldsValues($customFields);

            //Отправляем контакт на аккаунт

            $contact = $apiClient->contacts()->addOne($contact);

            //Подключаем его к сделке и обновляем сделку
            $link = new LinksCollection();
            $link->add($lead);

            $contact = $apiClient->contacts()->link($contact, $link);

            $apiClient->leads()->updateOne($lead);

            return redirect('/');
        } else {
            $contactFilters = new ContactsFilter();
            $contactFilters->setIds(session('contactId'));
            $contact = $apiClient->contacts()->get($contactFilters, [EntityTypesInterface::LEADS]);
            
            $leadFilters = new LeadsFilter();
            $leadFilters->setIds($contact[0]->getLeads()[0]->getId());
            $lead = $apiClient->leads()->get($leadFilters, [EntityTypesInterface::CONTACTS]);

            if (!Is_set::unCompletedLead($lead, session('contactId'))) {
                //Создаём покупателя
                $customer = new CustomerModel();

                $customer->setName($contactData['first_name'] . ' ' . $contactData['last_name']);

                    $customer = $apiClient->customers()->addOne($customer);

                //Берём уже существующий контакт и присоединяем его к покупателю
                $contact = $apiClient->contacts()->getOne(session('contactId'));

                $link = new LinksCollection();
                $link->add($contact);

                $apiClient->customers()->link($customer, $link);

                dump('создан покупатель, привязанный к уже существующему контакту ' . $contactData['first_name'] . ' ' . $contactData['last_name']);
                sleep(3);

                return redirect('/');
            } else {
                //Создаём примечание для сделки
                $note = new CommonNote();
                
                $note->setText('Доделайте в ближайшее время')
                        ->setResponsibleUserId(session('$contactId'))
                        ->setEntityId(session('leadId'));

                $apiClient->notes(EntityTypesInterface::LEADS)->addOne($note);

                dump('Предыдущая сделка ещё не завершена, мы постараемся её в скором времени завершить');
                sleep(3);

                return redirect('/');
            }
        }
    }
}
