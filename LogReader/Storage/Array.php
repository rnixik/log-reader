<?php

require_once 'LogReader/Storage/Interface.php';

class LogReader_Storage_Array implements LogReader_Storage_Interface {
    
    /**
     *
     * @var array
     */
    protected $_data = array();


    /**
     * 
     * @return array
     */
    public function load() {
        return $this->_data;
    }

    public function save(\LogReader_Item_Abstract $item) {
        $this->_data[] = $item;
    }
    
    /**
     * Returns unique errors
     * 
     * @return array
     */
    public function loadUnique() {
        $uniqRows = array();
        foreach ($this->_data as $item) {
            $itemId = $item->getId();
            if (isset($uniqRows[$itemId])) {
                $newTime = strtotime($item->getTimestamp());
                $oldTime = strtotime($uniqRows[$itemId]->getTimestamp());
                if ($newTime > $oldTime) {
                    $uniqRows[$itemId] = $item;
                }
            } else {
                $uniqRows[$item->getId()] = $item;
            }
        }
        return $uniqRows;
    }

}
