<?php

class MageProfis_RichSnippets_Block_Product
extends Mage_Catalog_Block_Product_Abstract
{
    public $_rating_value = -1;
    public $_rating_count = -1;

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
     * get Rating Value
     */
    public function getRatingValue()
    {
        $this->getRatingData();
        return $this->_rating_value;
    }
    
    /**
     * get Rating Count
     */
    public function getRatingCount()
    {
        $this->getRatingData();
        return $this->_rating_count;
    }
    
    public function getRatingData()
    {
        if ($this->_rating_value!=-1 || $this->_rating_count!=-1) return true;
        
        $this->_rating_value = false;
        $this->_rating_count = false;
        
        try {
            $summaryData = Mage::getModel('review/review_summary')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($this->getProduct()->getId());

            if(!$summaryData->getRatingSummary()) return false;

            $rating_value = ($summaryData->getRatingSummary() / 100) * 5;
            $rating_value = number_format($rating_value, 1, '.', '');

            $this->_rating_value = $rating_value;
            $this->_rating_count = $summaryData->getReviewsCount();
            
            return true;
        } catch (Exception $e) {
            //
            //  \,,/(^_^)\,,/
            //
        }
        
        return false;
    }
}