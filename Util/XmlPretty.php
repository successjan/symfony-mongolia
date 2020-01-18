<?php

use Symfony\Component\DependencyInjection\SimpleXMLElement;

/**
 * Description of XmlPrettyPrint
 *
 * @author Satjan
 */
class XmlPretty {

  public static function printPrettyXml($xml, $html_output = false) {
    $xml_obj = new SimpleXMLElement($xml);
    $level = 4;
    $indent = 0;
    $pretty = array();

    $tmpXml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

    if (count($tmpXml) && preg_match('/^<\?\s*xml/', $tmpXml[0])) {
      $pretty[] = array_shift($tmpXml);
    }

    foreach ($tmpXml as $el) {
      if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
        $pretty[] = str_repeat(' ', $indent) . $el;
        $indent += $level;
      } else {
        if (preg_match('/^<\/.+>$/', $el)) {
          $indent -= $level;
        }
        if ($indent < 0) {
          $indent += $level;
        }
        $pretty[] = str_repeat(' ', $indent) . $el;
      }
    }

    $resultXml = implode("\n", $pretty);

    return ($html_output) ? htmlentities($resultXml) : $resultXml;
  }

}