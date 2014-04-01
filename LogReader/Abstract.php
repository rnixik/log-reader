<?php

abstract class LogReader_Abstract {
    
    /**
     *
     * @var SplFileObject
     */
    protected $_file;
    
    /**
     *
     * @var string
     */
    protected $_filename;
    
    /**
     *
     * @var LogReader_Storage_Interface
     */
    protected $_storage;
    
    public function __construct($filename = '', $storage = null) {
        if ($filename) {
            $this->setFile($filename);
        }
        if ($storage) {
            $this->setStorage($storage);
        }
    }
    
    public function setFile($filename) {
        $this->_filename = $filename;
        if (is_readable($filename)) {
            $this->_file = new SplFileObject($filename);
        } else {
            throw new LogReader_Exception("File '$filename' is not readable");
        }
    }
    
    public function setStorage(LogReader_Storage_Interface $storage) {
        $this->_storage = $storage;
    }
    
    abstract public function read();
    
    
    /**
     * 
     * @return LogReader_Storage_Interface
     */
    public function getStorage() {
        return $this->_storage;
    }
    
    /**
     * 
     * @return string
     */
    public function getFilename() {
        return $this->_filename;
    }
}