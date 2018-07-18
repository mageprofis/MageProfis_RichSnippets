<?php

class MageProfis_RichSnippets_Block_Product
extends Mage_Catalog_Block_Product_Abstract
{
    public $_rating_value = -1;
    public $_rating_count = -1;

    protected $_breadcrumb = null;
    protected $_productModel = null;

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
        $desc = strip_tags(str_replace(array("/n","/r"),'',$this->getProduct()->getShortDescription()));
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
     * 
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct(){
        if (is_null($this->_productModel))
        {
            if( parent::getProduct()->getTypeId() != 'configurable' ){
                $this->_productModel = parent::getProduct();
                return $this->_productModel;
            }
            
            $childId = Mage::getSingleton('core/session')->getChildProductId();
            Mage::getSingleton('core/session')->setChildProductId(null);
            
            if($childId){
                $this->_productModel = Mage::getModel('catalog/product')->load($childId);
                return $this->_productModel;
            }
            $this->_productModel = parent::getProduct();
        }
        return $this->_productModel;
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

    /**
     * 
     * @return boolean
     */
    public function getRatingData()
    {
        if (Mage::helper('core')->isModuleEnabled('Mage_Review')
                && Mage::getConfig()->getModuleConfig('Mage_Review')->is('active', 'true'))
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
        } else {
            $this->_rating_value = false;
            $this->_rating_count = false;
        }
        return false;
    }
}
