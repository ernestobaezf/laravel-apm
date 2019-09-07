<?php
namespace LaravelAPM\logger\storage;

interface StorageInterface
{
    public function save($data);
}
