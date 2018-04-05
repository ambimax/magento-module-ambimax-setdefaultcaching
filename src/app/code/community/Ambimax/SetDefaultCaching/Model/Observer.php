<?php

class Ambimax_SetDefaultCaching_Model_Observer
{
    const DEFAULT_CACHETIME = 86400;

    /**
     * @param Varien_Event_Observer $observer
     * @throws Varien_Exception
     */
    public function setDefaultBlockCaching(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();

        if ( !Mage::getStoreConfigFlag('catalog/ambimax_setdefaultcaching/enabled') ) {
            return;
        }

        if ( $block instanceof Mage_Page_Block_Html_Head ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/page_html_head');
            $block->addCacheTag($this->getRequestUri());
        }

        if ( $block instanceof Mage_Catalog_Block_Product_View ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/catalog_product_view');
        }

        if ( $block instanceof Mage_Page_Block_Html_Breadcrumbs ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/page_html_breadcrumbs');
            $block->addCacheTag($this->getRequestUri());
        }

        if ( $block instanceof Mage_Catalog_Block_Category_View ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/catalog_category_view');
            $block->addCacheTag($this->getRequestUri());
        }

        if ( $block instanceof Mage_Catalog_Block_Layer_View ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/catalog_layer_view');
            $block->addCacheTag($this->getRequestUri());
        }

        if ( $block instanceof Mage_Cms_Block_Block ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/cms_block');
        }

        if ( $block instanceof Mage_Cms_Block_Page ) {
            $this->_setCacheLifetime($block, 'catalog/ambimax_setdefaultcaching/cms_page');
        }
    }

    /**
     * @param Varien_Object $block
     * @param int $lifetime
     * @throws Varien_Exception
     */
    protected function _setCacheLifetime(Varien_Object $block, $configPath = null, $lifetime = self::DEFAULT_CACHETIME)
    {
        $force = Mage::getStoreConfigFlag('catalog/ambimax_setdefaultcaching/force');
        $noCaching = null === $block->getCacheLifetime();
        $setDefaultCacheLifetime = $force || $noCaching;

        if ( !$setDefaultCacheLifetime ) {
            // block has some caching lifetime and cache lifetime is not forced
            return;
        }

        if ( $configPath ) {
            $lifetime = strtolower(Mage::getStoreConfig($configPath));
        }

        if ( '' === $lifetime || 'null' === $lifetime ) {
            return $block->setCacheLifetime(null);
        }

        $block->setCacheLifetime((int) abs($lifetime));
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return Mage::app()->getRequest()->getOriginalRequest()->getRequestUri();
    }
}