<?php
namespace LaravelAPM\logger\storage;

class File implements StorageInterface
{
    protected $_file;

    public function __construct($file) 
    {
        $this->_file = $file;
    }
    
    public function save($data)
    {
        $json = json_encode($data);
        return file_put_contents($this->_file, $json.PHP_EOL, FILE_APPEND);
    }
}
