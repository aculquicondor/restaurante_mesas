<?php

// src/Restaurant/TablesBundle/Document/Cliente.php
namespace Restaurant\TablesBundle\Document;

class Reservation
{
    protected $id;
    protected $tables = array();
    protected $client;
}