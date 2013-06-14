<?php
require_once('cdata.php');
// functions to support Kayako LoginShare


// See if array has all params for a valid loginshare request
// E.g. pass $_POST to validate it
function loginshare_parse_request($p) {
  $errflag = 0;
  $data = array();

  if (isset($p['username'])) {
    $data['username'] = $p['username'];
  } else {
    $errflag++;
  }

  if (isset($p['password'])) {
    $data['password'] = $p['password'];
  } else {
    $errflag++;
  }

  if (isset($p['ipaddress'])) {
    $data['ipaddress'] = $p['ipaddress'];
  } else {
    $errflag++;
  }

  if (isset($p['interface'])) {
    $data['interface'] = $p['interface'];
  } else {
    $errflag++;
  }

  if ($errflag != 0) {
    return FALSE;
  }
  return $data;
}

// check if interface used is in array of allowed interfaces
function loginshare_interface_allowed($interface, $allowed) {
  $is_allowed = FALSE;
  foreach($allowed as $val) {
    if ($val == $interface) {
      $is_allowed = TRUE;
      break;
    }
  }

  return $is_allowed;
}

// Generate loginshare common XML
function loginshare_xml() {
  $x = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><loginshare/>');
  return $x;
}

// Generate error message
function loginshare_error($message) {
  $xml = loginshare_xml();
  $xml->addChild('result', 0);
  $xml->addChildCData('message', $message);
  return $xml->asXML();
}

function loginshare_ok($first, $last, $email, $team='', $title='', $mobile='', $signature='') {
  $xml = loginshare_xml();
  $xml->addChild('result', 1);
  $staff = $xml->addChild('staff');
  $staff->addChildCData('firstname', $first);
  $staff->addChildCData('lastname', $last);
  $staff->addChildCData('email', $email);
  $staff->addChildCData('team', $team);
  $staff->addChildCData('designation', $title);
  $staff->addChildCData('mobilenumber', $mobile);
  $staff->addChildCData('signature', $signature);
  return $xml->asXML();
}

?>
