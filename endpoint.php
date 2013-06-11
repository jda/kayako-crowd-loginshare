<?php
require_once('common.php');
require_once('loginshare.php');

// only do work if we get a request that appears valid
// kayako sends POSTs so if it isn't POST, bail
if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
  exit(1);
}

// kayako sends specific parameters so if they don't exist, bail
$loginshare = loginshare_parse_request();
if ($loginshare === FALSE) {
  exit(1);
}

// load, parse, and validate config
$raw_config = file_get_contents('config.json');
if ($raw_config === FALSE) {
  error_log("$app: config file could not be opened: config.json");
  kcl_header();
  print loginshare_error("$app config file not found");
  exit(1);
}

$config = json_decode($raw_config);
if ($config === FALSE) {
  error_log("$app: config file is not valid json");
  kcl_header();
  print loginshare_error("$app config file is not parsable");
  exit(1);
}

if (kcl_valid_config($config) === FALSE) {
  error_log("$app: config file is not valid");
  kcl_header();
  print loginshare_error("$app config file is not valid");
  exit(1);
}


?>
