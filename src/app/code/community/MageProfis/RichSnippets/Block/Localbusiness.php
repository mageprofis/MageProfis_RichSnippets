<?php

class MageProfis_RichSnippets_Block_Localbusiness extends Mage_Core_Block_Template
{
    public function getStoreName()
    {
        return Mage::getStoreConfig('general/store_information/name');
    }

    public function getAddress($code)
    {
        return Mage::getStoreConfig('general/impressum/' . $code);
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

}
