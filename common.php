<?php
// common functions

// header to be sent before all requests
function kcl_header() {
  header('Content-Type: application/xml; charset=utf-8');
}

// validate config
function kcl_valid_config($c) {
  $errflag = 0;
  
  if (!isset($c['crowd_appname'])) {
    $errflag++;
  }

  if (!isset($c['crowd_apppasswd'])) {
    $errflag++;
  }

  if (!isset($c['crowd_baseurl'])) {
    $errflag++;
  }

  if (!isset($c['kayako_interfaces'])) {
    $errflag++;
  }

  if (count($c['kayako_interfaces']) <= 0) {
    $errflag++;
  }

  if ($errflag > 0) {
    return FALSE;
  }
  
  return TRUE;
}

// standard error handler
function kcl_error($message) {
  $msg = "kayako-crowd-loginshare: $message";
  error_log($msg);
  kcl_header();
  print loginshare_error($msg);
  exit(1);
}

?>
