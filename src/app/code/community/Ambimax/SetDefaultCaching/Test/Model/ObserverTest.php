<?php

class Ambimax_SetDefaultCaching_Test_Model_ObserverTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var Ambimax_SetDefaultCaching_Model_Observer
     */
    protected $_singleton;

    /**
     * @return array
     */
    public function getObserverMock($blockClass)
    {
        $block = Mage::app()->getLayout()->createBlock($blockClass, 't0');

        $observerMock = $this->getMockBuilder('Varien_Event_Observer')
            ->setMethods(['getBlock'])
            ->getMock();

        $observerMock
            ->expects($this->once())
            ->method('getBlock')
            ->willReturn($block);

        return array($block, $observerMock);
    }

    /**
     * @param $expectedLifetime
     * @param $blockClass
     * @throws Varien_Exception
     */
    public function assertDefaultCacheLifetime($expectedLifetime, $blockClass, $presetLifetime = null)
    {
        list($block, $observerMock) = $this->getObserverMock($blockClass);

        if ( !is_null($presetLifetime) ) {
            $block->setCacheLifetime($presetLifetime);
        }

        $this->_singleton->setDefaultBlockCaching($observerMock);

        return $this->assertSame($expectedLifetime, $block->getCacheLifetime());
    }

    /**
     * Init
     */
    public function setUp()
    {
        parent::setUp();

        $coreSessionMock = $this
            ->getMockBuilder('Mage_Catalog_Model_Session')
            ->setMethods(array('start'))
            ->getMock();

        $this->replaceByMock('singleton', 'core/session', $coreSessionMock);

        $this->_singleton = Mage::getSingleton('ambimax_setdefaultcaching/observer');

        Mage::register('current_product', new Varien_Object(['id' => 1]), true);
    }

    public function testPageBlockIsRewritten()
    {
        $this->assertInstanceOf(
            Ambimax_SetDefaultCaching_Block_Cms_Page::class,
            Mage::app()->getLayout()->createBlock('cms/page')
        );
    }

    /**
     * @loadFixture ~Ambimax_SetDefaultCaching/default
     * @throws Varien_Exception
     */
    public function testBlocksGetConfiguredLifetime()
    {
        $this->assertDefaultCacheLifetime(86401, 'Mage_Page_Block_Html_Head', 200);
        $this->assertDefaultCacheLifetime(86402, 'Mage_Catalog_Block_Product_View');
        $this->assertDefaultCacheLifetime(86403, 'Mage_Page_Block_Html_Breadcrumbs');
        $this->assertDefaultCacheLifetime(86404, 'Mage_Catalog_Block_Category_View');
        $this->assertDefaultCacheLifetime(86405, 'Mage_Catalog_Block_Layer_View');
        $this->assertDefaultCacheLifetime(86406, 'Mage_Cms_Block_Block');
        $this->assertDefaultCacheLifetime(86407, 'Ambimax_SetDefaultCaching_Block_Cms_Page');
    }

    /**
     * @loadFixture ~Ambimax_SetDefaultCaching/unforced
     * @throws Varien_Exception
     */
    public function testNoOverwriteWhenLifetimeAlreadyExists()
    {
        $this->assertDefaultCacheLifetime(200, 'Mage_Page_Block_Html_Head', 200);
        $this->assertDefaultCacheLifetime(false, 'Mage_Cms_Block_Block', false);
        $this->assertDefaultCacheLifetime(0, 'Ambimax_SetDefaultCaching_Block_Cms_Page', 0);
    }

    /**
     * @loadFixture ~Ambimax_SetDefaultCaching/strings
     * @throws Varien_Exception
     */
    public function testSetLifetimeFromString()
    {
        $this->assertDefaultCacheLifetime(null, 'Mage_Page_Block_Html_Head');
        $this->assertDefaultCacheLifetime(0, 'Mage_Catalog_Block_Product_View');
        $this->assertDefaultCacheLifetime(null, 'Mage_Page_Block_Html_Breadcrumbs');
        $this->assertDefaultCacheLifetime(400, 'Mage_Catalog_Block_Category_View');
        $this->assertDefaultCacheLifetime(200, 'Mage_Catalog_Block_Layer_View');
        $this->assertDefaultCacheLifetime(0, 'Mage_Cms_Block_Block');
        $this->assertDefaultCacheLifetime(null, 'Ambimax_SetDefaultCaching_Block_Cms_Page');
    }
}