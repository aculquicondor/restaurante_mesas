<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class MenuItemRepository extends  DocumentRepository
{
    public function getAvailable(){
        $menusItemsAvailables = $this->findBy(array('avaiable' => true));
        return $menusItemsAvailables;
    }
}