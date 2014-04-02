<?php

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
