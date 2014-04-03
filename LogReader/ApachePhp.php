<?php

require_once 'LogReader/Abstract.php';
require_once 'LogReader/Item/ApachePhp.php';

class LogReader_ApachePhp extends LogReader_Abstract {

    public function read() {
        $item = new LogReader_Item_ApachePhp();
        while (!$this->_file->eof()) {

            if (preg_match('/^\[(?<date>.+?)\] \[(?:.+?)\] \[(?<client>.+?)\] (?<php_type>PHP)?(?<message>.+?)(, referer: (?<referer>.+))?$/', $this->_file->fgets(), $matches)) {
                $date = $matches['date'];
                $message = $matches['message'];
                
                if (preg_match('/^(Stack trace|[\d])/', trim($message))) {
                    //this line is part of stack trace
                    $item->appendStackTrace($message);
                } else {
                    $this->_save($item);
                    
                    $item = new LogReader_Item_ApachePhp();
                    
                    $timestamp = date('Y-m-d H:i:s', strtotime($date));
                    $item->setTimestamp($timestamp);
                    if (!empty($matches['php_type'])) {
                        $type = $this->_getType($message);
                    } else {
                        $type = 'Apache';
                    }
                    $item->setType($type);
                    if (isset($matches['referer'])) {
                        $item->setReferer($matches['referer']);
                    }
                    $item->setMessage($message);    
                }
            }
        }
        
        $this->_save($item);
    }

    protected function _getType($message) {
        if (preg_match('/^([a-zA-Z0-9 ]+): /', $message, $matches) && isset($matches[1])) {
            return trim($matches[1]);
        }
    }
    
    protected function _save(LogReader_Item_ApachePhp $item) {
        if ($item->getMessage() && $this->_storage) {
            $stackTrace = $item->getStackTrace();
            $messagesArray = array_merge(array($item->getMessage()), $stackTrace);
            $item->setMessage(implode("\n", $messagesArray));
            $this->_storage->save($item);
        }
    }

}
