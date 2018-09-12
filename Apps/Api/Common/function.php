<?php 
/**
 * xml转换为array
 * @param SimpleXMLElement $xml
 */
function xml2array(SimpleXMLElement $xml){
    $arr = array();
    foreach ($xml as $key=>$element){
        $e = get_object_vars($element);
        if(empty($e)){
            $arr[$key] = trim($element);
        }else{
            $arr[$key] = ($element instanceof SimpleXMLElement) ? xml2array($element) : $e;
        }
    }
    return $arr;
}