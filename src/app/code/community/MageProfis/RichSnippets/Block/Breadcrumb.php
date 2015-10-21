<?php

class MageProfis_RichSnippets_Block_Breadcrumb
extends Mage_Core_Block_Template
{
    /**
     * encode json with extra options
     * 
     * @param mixed $string
     * @return string
     */
    public function jsonEncode($string)
    {
        return Mage::helper('richsnippets')->jsonEncode($string);
    }

    /**
     * get Breadcrumb Path without Product
     * 
     * @return array
     */
    public function getBreadcrumbPath()
    {
        if (is_null($this->_breadcrumb))
        {
            $breadcrumb = Mage::helper('catalog')->getBreadcrumbPath();
            //array_pop($breadcrumb); // remove last item
            $this->_breadcrumb = $breadcrumb;
        }
        return $this->_breadcrumb;
    }
}