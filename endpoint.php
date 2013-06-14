<?php
require_once('common.php');
require_once('loginshare.php');
require_once('crowd.php');

// only do work if we get a request that appears valid
// kayako sends POSTs so if it isn't POST, bail
if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
  exit(1);
}

// kayako sends specific parameters so if they don't exist, bail
$loginshare = loginshare_parse_request($_POST);
if ($loginshare === FALSE) {
  exit(1);
}

// load, parse, and validate config
$raw_config = file_get_contents('config.json');
if ($raw_config === FALSE) {
  kcl_error("config file could not be opened: config.json");
}

$config = json_decode($raw_config, true);
if ($config === FALSE) {
  kcl_error("config file is not parsable");
}

if (kcl_valid_config($config) === FALSE) {
  kcl_error("config file is not valid");
}

// check if request is for a interface that we allow
if (!loginshare_interface_allowed($loginshare['interface'], $config['kayako_interfaces'])) {
  kcl_error("interface not allowed");
}

// hit up crowd

$c = new Crowd($config['crowd_baseurl'], $config['crowd_appname'], $config['crowd_apppasswd']);
$a = $c->authenticate($loginshare['username'], $loginshare['password']);
if (isset($a['reason'])) {
  kcl_error($a['message']);
}

print loginshare_ok($a['first-name'], $a['last-name'], $a['email']);

?>
