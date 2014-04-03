<?php

class LogReader_Item_Nginx extends LogReader_Item_Abstract {
    
    /**
     *
     * @var string
     */
    protected $_referrer = '';
    
    /**
     *
     * @var string
     */
    protected $_request = '';

    /**
     *
     * @var string
     */
    protected $_host = '';
    
    
    public function getRequest() {
        return $this->_request;
    }

    public function getHost() {
        return $this->_host;
    }

    public function setRequest($request) {
        $this->_request = $request;
    }

    public function setHost($host) {
        $this->_host = $host;
    }

    
    public function getReferrer() {
        return $this->_referrer;
    }

    public function setReferrer($referer) {
        $this->_referrer = $referer;
    }

    public function getRequestUrl() {
        $request = preg_replace('/^GET (.+) HTTP.+/', '$1', $this->getRequest());
        $url = 'http://' . $this->getHost() . $request;
        return $url;
    }

}
