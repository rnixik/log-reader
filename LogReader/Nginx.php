<?php

class LogReader_Nginx extends LogReader_Abstract {
    
    
    public function read() {
        //2014/03/28 15:17:15 [error] 13385#0: *197692 open() "/var/www/logo.png" failed (2: No such file or directory), client: 192.168.1.1, server: cs.google.com, request: "GET /static/img/logo.png HTTP/1.1", host: "cs.google.com", referrer: "http://google.com"
        while (!$this->_file->eof()) {
            if (preg_match('/^([0-9\/]+ [0-9:]+) \[.+?\] .+? .+? (.+), client: .+?, request: "(.+)", host: "(.+)", referrer: "(.+)"/', $this->_file->fgets(), $matches)) {
                
                $item = new LogReader_Item_Nginx();

                $timestamp = date('Y-m-d H:i:s', strtotime($matches[1]));
                $item->setTimestamp($timestamp);
                $message = $matches[2];
                $type = $this->_getType($message);
                $item->setType($type);
                $item->setMessage($message);
                $item->setRequest($matches[3]);
                $item->setHost($matches[4]);
                $item->setReferrer($matches[5]);

                if ($this->_storage) {
                    $this->_storage->save($item);
                }
            }
        }
    }

    protected function _getType($message) {
        if (preg_match('/^([a-zA-Z0-9 ()]+) "/', $message, $matches) && isset($matches[1])) {
            return trim($matches[1]);
        }
    }

}
