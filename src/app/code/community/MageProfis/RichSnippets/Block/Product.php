<?php

class MageProfis_RichSnippets_Block_Product
extends Mage_Catalog_Block_Product_Abstract
{

    protected $_breadcrumb = null;

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
     * get Currency ISO Code
     * 
     * @return string
     */
    public function getCurrencyCode()
    {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * 
     * @return string
     */
    public function getShortDescription()
    {
        $desc = strip_tags($this->getProduct()->getShortDescription());
        return $this->jsonEncode($desc);
    }

    /**
     * get Final Price with Tax Calculation
     * 
     * @return string
     */
    public function getFinalPriceInclTax()
    {
        $price = Mage::helper('tax')
            ->getPrice($this->getProduct(), $this->getProduct()->getFinalPrice(), true);
        return number_format($price, 2, '.', '');
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
            array_pop($breadcrumb); // remove last item
            $this->_breadcrumb = $breadcrumb;
        }
        return $this->_breadcrumb;
    }
}