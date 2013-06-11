<?php
// functions to support Kayako LoginShare

// Get loginshare data from HTTP POST or return false if required vars not present
function loginshare_parse_request() {
  $errflag = 0;
  $data = array();

  if (isset($_POST['username'])) {
    $data['username'] = $_POST['username'];
  } else {
    $errflag++;
  }

  if (isset($_POST['password'])) {
    $data['password'] = $_POST['password'];
  } else {
    $errflag++;
  }

  if (isset($_POST['ipaddress'])) {
    $data['ipaddress'] = $_POST['ipaddress'];
  } else {
    $errflag++;
  }

  if (isset($_POST['interface'])) {
    $data['interface'] = $_POST['interface'];
  } else {
    $errflag++;
  }

  if ($errflag != 0) {
    return FALSE;
  }
  return $data;
}

// Generate loginshare common XML
function loginshare_xml() {
  $x = new SimpleXMLElement('<?xml version="1.0 encoding="UTF-8"?><loginshare/>');
  return $x;
}

// Generate error message
function loginshare_error($message) {
  $xml = loginshare_xml();
  $xml->addChild('result', 0);
  $xml->addChild('message', $message);
  return $xml->asXML();
}

?>
