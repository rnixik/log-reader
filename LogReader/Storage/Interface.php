<?php

require_once 'LogReader/Item/Abstract.php';

interface LogReader_Storage_Interface {
    
    public function save(LogReader_Item_Abstract $item);
    
    public function load();
    
    public function loadUnique();
}