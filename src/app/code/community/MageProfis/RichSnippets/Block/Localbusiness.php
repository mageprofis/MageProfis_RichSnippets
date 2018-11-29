<?php

class MageProfis_RichSnippets_Block_Localbusiness extends Mage_Core_Block_Template
{
    public function getStoreName()
    {
        $out = Mage::getStoreConfig('general/store_information/name');
        if(!$out)
        {
            $out = Mage::getStoreConfig('general/imprint/company_first');
        }
        return $out;
    }

    public function getAddress($code)
    {
        $out = Mage::getStoreConfig('general/impressum/' . $code);
        if(!$out)
        {
            $out = Mage::getStoreConfig('general/imprint/' . $code);
        }
        return $out;
    }

    public function getTelephone()
    {
        $val = Mage::getStoreConfig('general/store_information/phone');

        if (!$val)
            return '';

        $val = preg_replace('/[^0-9]/s', '', $val);
        
        if (substr($val, 0, 4) != '0049' || substr($val, 0, 2) != '49')
            $val = '+49' . substr($val, 1);

        return $val;
    }

    public function getEmail()
    {
        return Mage::getStoreConfig('trans_email/ident_general/email');
    }

    public function getLogoUrl()
    {
        $logoSrc = Mage::getStoreConfig('design/header/logo_src');
        return $this->getSkinUrl($logoSrc);
    }

    public function getDescription()
    {
        return Mage::getStoreConfig('richsnippets/general/description');
    }
    
    public function getPricerange()
    {
        return Mage::getStoreConfig('richsnippets/general/pricerange');
    }
    
    public function getOpeninghoursn()
    {
        $hours = Mage::getStoreConfig('richsnippets/general/openinghours');
        
        if(!trim($hours))
            return false;
        
        $hours = explode("\n", $hours);
        $hours = array_filter($hours);
        
        return Mage::helper('core')->jsonEncode($hours);
    }
}
