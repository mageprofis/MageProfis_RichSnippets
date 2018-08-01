<?php

class MageProfis_RichSnippets_Model_Cron_Trustedshops
{
    public function run()
    {
        foreach (Mage::app()->getStores() as $_store)
        {
            $store_id = $_store->getId();
            $trusted_shops_id = Mage::getStoreConfig('richsnippets/rating/trustedshops_id', $store_id);

            if (!Mage::getStoreConfig('richsnippets/rating/active', $store_id) || !$trusted_shops_id)
                continue;

            try {
                //get data from ts api
                $data = $this->fetchApi($trusted_shops_id);

                //clean data...
                /*
                  $data = $data['response']['data']['shop'];
                  unset($data["tsId"]);
                  unset($data["url"]);
                  unset($data["name"]);
                  unset($data["languageISO2"]);
                  unset($data["targetMarketISO3"]);
                  unset($data["qualityIndicators"]["reviewIndicator"]["reviewIndicatorPeriodSummary"]);
                  unset($data["qualityIndicators"]["reviewIndicator"]["overallMarkDescriptionGUILang"]);
                  unset($data["qualityIndicators"]["reviewIndicator"]["overallMarkDescription"]);
                 */

                //save data
                Mage::getConfig()->saveConfig('richsnippets/rating/trustedshops_result', Mage::helper('core')->jsonEncode($data), 'stores', $store_id);
            } catch (Exception $exc) {
                Mage::log($exc->getMessage(), null, 'mageprofis_richsnippets_trustedshops.log');
            }
        }


        return $this;
    }

    public function fetchApi($trusted_shops_id)
    {
        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
            'timeout' => 15, //Timeout in no of seconds
            'header' => false,
        ));
        $feed_url = "https://api.trustedshops.com/rest/public/v2/shops/" . $trusted_shops_id . "/quality";
        $curl->write(Zend_Http_Client::GET, $feed_url, '1.0', array('Content-Type: application/json'));
        $data = $curl->read();
        if ($data === false)
        {
            return false;
        }
        //$data = preg_split('/^r?$/m', $data, 2);
        //$data = trim($data[1]);
        $curl->close();

        try {
            return Mage::helper('core')->jsonDecode($data);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'mageprofis_richsnippets_trustedshops.log');
        }
    }

}
