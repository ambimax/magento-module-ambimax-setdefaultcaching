<?php

class Ambimax_SetDefaultCaching_Block_Cms_Page extends Mage_Cms_Block_Page
{
    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        if ( $cacheKeyInfo = $this->_getData('cache_key_info') ) {
            return $cacheKeyInfo;
        }

        return parent::getCacheKeyInfo();
    }


    /**
     * @param $cacheKey
     * @throws Varien_Exception
     */
    public function addCacheKeyInfo($cacheKey)
    {
        $keys = $this->getCacheKeyInfo();
        $keys[] = $cacheKey;
        $this->setCacheKeyInfo($keys);
    }
}