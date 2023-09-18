<?php

namespace App\Models;

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Filters\CatalogElementsFilter;

class GetProduct
{
    /**
     * @param \AmoCRM\Client\AmoCRMApiClient
     * @param int 
     */
    static function get($apiClient, &$price) {
        $catalog = $apiClient->catalogs()->get()->getBy('name', 'Товары');
        $catalogElementsService = $apiClient->catalogElements($catalog->getId());
        $catalogElementsFilter = new CatalogElementsFilter();
        $catalogElementsCollection = $catalogElementsService->get($catalogElementsFilter);

        $product1 = $catalogElementsCollection->getBy('name', 'Ноутбук');
        $product1->setQuantity(1);
        $product2 = $catalogElementsCollection->getBy('name', 'Смартфон');
        $product2->setQuantity(1);

        $price = 0;

        foreach([$product1, $product2] as $item){
            $price += $item->getCustomFieldsValues()[1]->values[0]->value * $item->getQuantity();
        }

        return [$product1, $product2];
    }
}
