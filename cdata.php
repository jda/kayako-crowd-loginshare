<?php
// Extend SimpleXML to support CDATA per:
// http://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement
// and comment from Alexandre FERAUD at http://php.net/manual/en/simplexmlelement.addchild.php

class SimpleXMLExtended extends SimpleXMLElement {
  /**
   * Add CDATA text in a node
   * @param string $cdata_text The CDATA value to add
   */
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this);
    $no = $node->ownerDocument;
    $node->appendChild($no->createCDATASection($cdata_text));
  }

  /**
   * Create a child with CDATA valu
   * @param string $name The name of the child element to add. 
   * @param string $cdata_text The CDATA value of the child element.
   */
  public function addChildCData($name, $cdata_text) {
    $child = $this->addChild($name);
    $child->addCData($cdata_text);
  }
}

?>
