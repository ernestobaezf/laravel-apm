<?php

use LaravelAPM\logger\Saver;

$options = getopt('f:');

if (!isset($options['f'])) {
    throw new InvalidArgumentException('You should define a file to be loaded');
} else {
    $file = $options['f'];
}

if (!is_readable($file)) {
    throw new InvalidArgumentException($file.' isn\'t readable');
}

$fp = fopen($file, 'r');
if (!$fp) {
    throw new RuntimeException('Can\'t open '.$file);
}

$saver = Saver::factory();

while (!feof($fp)) {
    $line = fgets($fp);
    $data = json_decode($line, true);
    if ($data) {
        $saver->save($data);
    }
}

fclose($fp);
