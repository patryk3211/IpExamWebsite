<?php
function load_config() {
  $config_text = file_get_contents(__DIR__.'/../config.txt');

  $lines = explode("\n", $config_text);
  $config = [];
  foreach($lines as $line) {
    $line = trim($line);
    if(strlen($line) == 0)
      continue;
    if($line[0] == '#')
      continue;
    $pos = strpos($line, '=');
    if($pos === false)
      continue;
    
    $key = trim(substr($line, 0, $pos));
    $value = trim(substr($line, $pos + 1));
    $config[$key] = $value;
  }
  
  $addresses = file_get_contents(__DIR__.'/../adresy.txt');
  $lines = explode("\n", $addresses);
  $config['addresses'] = [];
  foreach($lines as $addr) {
    if(preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d{1,2}$/', $addr)) {
      array_push($config['addresses'], $addr);
    }
  }

  return $config;
}

function time_limit() {
  $parsed = date_parse($GLOBALS['config']['time_limit']);
  return $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
}

$GLOBALS['config'] = load_config();

