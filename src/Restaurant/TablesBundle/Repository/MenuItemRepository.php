<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class MenuItemRepository extends  DocumentRepository
{
    public function getAvailable(){
        $menusItemsAvailable = $this->findBy(array('available' => true));
        return $menusItemsAvailable;
    }
}