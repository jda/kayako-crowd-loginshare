<?php
class Crowd {
  private $baseurl = false;
  private $appname = false;
  private $appsecret = false;
  private $cookiejar = false;

  function __construct($url, $username, $password) {
    $this->baseurl = $url;
    $this->appname = $username;
    $this->appsecret = $password;
    $this->cookiejar = tempnam('/tmp', 'kcl');
  }

  function __destruct() {
    unlink($this->cookiejar);
  }

  private function stdheaders() {
    $h = array();
    array_push($h, "Content-Type: application/json");
    array_push($h, "Accept: application/json");
    return $h;
  }

  private function mkcurl() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $userpwd = $this->appname . ':' . $this->appsecret;
    curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
    curl_setopt($ch, CURLOPT_SSLVERSION,3);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "kayako-crowd-loginshare");
    curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiejar);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiejar);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    return $ch;
  }

  public function authenticate($username, $password) {
    $ch = $this->mkcurl();
    $url = $this->baseurl . "rest/usermanagement/1/authentication";
    $url .= '?username=' . $username;
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = array();
    $data['value'] = $password;
    
    $jdata = json_encode($data);
    
    $headers = $this->stdheaders();
    array_push($headers, 'Content-Length: ' . strlen($jdata));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jdata);

    $rawres = curl_exec($ch);
    $res = json_decode($rawres, true);
    return $res;
  }

}
?>
