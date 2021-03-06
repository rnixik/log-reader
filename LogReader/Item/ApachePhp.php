<?php

require_once 'LogReader/Item/Abstract.php';

class LogReader_Item_ApachePhp extends LogReader_Item_Abstract {
    
    protected $_clientIp;
    
    /**
     *
     * @var array
     */
    protected $_stackTrace = array();
    
    /**
     *
     * @var string
     */
    protected $_referer = '';
    
    
    public function getReferer() {
        return $this->_referer;
    }

    public function setReferer($referer) {
        $this->_referer = $referer;
    }

    
    /**
     * 
     * @param string $line
     */
    public function appendStackTrace($line) {
        $this->_stackTrace[] = $line;
    }
    
    /**
     * 
     * @return array
     */
    public function getStackTrace() {
        return $this->_stackTrace;
    }
    
    /**
     * 
     * @param string $clientIp
     */
    public function setClientIp($clientIp) {
        $this->_clientIp = $clientIp;
    }
    
    /**
     * 
     * @return string
     */
    public function getClientIp() {
        return $this->_clientIp;
    }

}
