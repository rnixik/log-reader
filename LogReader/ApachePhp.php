<?php

class LogReader_ApachePhp extends LogReader_Abstract {
    
    
    public function read() {
        $item = new LogReader_Item_ApachePhp();
        while (!$this->_file->eof()) {
            
            if (preg_match('/^\[(.+?)\] \[(.+?)\] \[(.+?)\] (PHP )?(.+), referer: (.+)/', $this->_file->fgets(), $matches)) {
                list ($raw, $date, $error, $client, $php, $message, $referer) = $matches;
                
                if (preg_match('/^(Stack trace|[\d])/', trim($message))) {
                    //this line is part of stack trace
                    $item->appendStackTrace($message);
                } else {
                    $timestamp = date('Y-m-d H:i:s', strtotime($date));
                    $item->setTimestamp($timestamp);
                    $type = $this->_getType($message);
                    $item->setType($type);
                    $item->setReferer($referer);
                    $stackTrace = $item->getStackTrace();
                    $messagesArray = array_merge(array($message), $stackTrace);
                    $item->setMessage(implode("\n", $messagesArray));
                    
                    if ($this->_storage) {
                        $this->_storage->save($item);
                    }
                    
                    $item = new LogReader_Item_ApachePhp();
                }
            }
            
        }
    }
    
    protected function _getType($message) {
        if (preg_match('/^([a-zA-Z0-9 ]+): /', $message, $matches) && isset($matches[1])) {
            return trim($matches[1]);
        }
    }

}
