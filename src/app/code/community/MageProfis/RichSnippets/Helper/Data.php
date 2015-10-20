<?php

class MageProfis_RichSnippets_Helper_Data
extends Mage_Core_Model_Abstract
{

    /**
     * encode json with extra options
     * 
     * @param mixed $string
     * @return string
     */
    public function jsonEncode ($string)
    {
        $options = 0;
        if (defined('JSON_UNESCAPED_UNICODE')) // available since >= PHP 5.4
        {
            $options = JSON_UNESCAPED_UNICODE;
        }
        $json = json_encode($string, $options);
        $json = str_replace('\\/','/', $json);      // less data
        $json = str_replace("\\u00a0", ' ', $json); // json space to normal space
        $json = str_replace("\\ ", ' ', $json);     // remove multiple slashes
        $json = str_replace("\\", "\\\\", $json);   // remove multiple slashes
        return $json;
    }
}