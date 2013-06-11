<?php
// Extend SimpleXML to support CDATA per:
// http://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement

class SimpleXMLExtended extends SimpleXMLElement {
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this);
    $no = $node->ownerDocument;
    $node->appendChild($no->createCDATASection($cdata_text));
  }
}

?>
