<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Rss
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Rss data helper
 *
 * @category   Mage
 * @package    Mage_Rss
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Rss_Helper_Data extends Mage_Core_Helper_Abstract
{
    /** @var Mage_Rss_Model_Session  */
    private $_rssSession;

    /** @var Mage_Admin_Model_Session  */
    private $_adminSession;

    public function __construct()
    {
        $this->_rssSession = Mage::getSingleton('rss/session');
        $this->_adminSession = Mage::getSingleton('admin/session');;
    }

    /**
     * Authenticate customer on frontend
     *
     */
    public function authFrontend()
    {
        if ($this->_rssSession->isCustomerLoggedIn()) {
            return;
        }
        list($username, $password) = $this->authValidate();
        $customer = Mage::getModel('customer/customer')->authenticate($username, $password);
        if ($customer && $customer->getId()) {
            Mage::getSingleton('rss/session')->settCustomer($customer);
        } else {
            $this->authFailed();
        }
    }

    /**
     * Authenticate admin and check ACL
     *
     * @param string $path
     */
    public function authAdmin($path)
    {
        if (!$this->_rssSession->isAdminLoggedIn() || !$this->_adminSession->isLoggedIn()) {
            list($username, $password) = $this->authValidate();
            Mage::getSingleton('adminhtml/url')->setNoSecret(true);
            $user = $this->_adminSession->login($username, $password);
        } else {
            $user = $this->_rssSession->getAdmin();
        }
        if ($user && $user->getId() && $user->getIsActive() == '1' && $this->_adminSession->isAllowed($path)) {
            $adminUserExtra = $user->getExtra();
            if ($adminUserExtra && !is_array($adminUserExtra)) {
                $adminUserExtra = Mage::helper('core/unserializeArray')->unserialize($user->getExtra());
            }
            if (!isset($adminUserExtra['indirect_login'])) {
                $adminUserExtra = array_merge($adminUserExtra, array('indirect_login' => true));
                $user->saveExtra($adminUserExtra);
            }
            $this->_adminSession->setIndirectLogin(true);
            $this->_rssSession->setAdmin($user);
        } else {
            $this->authFailed();
        }
    }

    /**
     * Validate Authenticate
     *
     * @param array $headers
     * @return array
     */
    public function authValidate($headers=null)
    {
        $userPass = Mage::helper('core/http')->authValidate($headers);
        return $userPass;
    }

    /**
     * Send authenticate failed headers
     *
     */
    public function authFailed()
    {
        Mage::helper('core/http')->authFailed();
    }

    /**
     * Disable using of flat catalog and/or product model to prevent limiting results to single store. Probably won't
     * work inside a controller.
     *
     * @return null
     */
    public function disableFlat()
    {
        /* @var $flatHelper Mage_Catalog_Helper_Product_Flat */
        $flatHelper = Mage::helper('catalog/product_flat');
        if ($flatHelper->isEnabled()) {
            /* @var $emulationModel Mage_Core_Model_App_Emulation */
            $emulationModel = Mage::getModel('core/app_emulation');
            // Emulate admin environment to disable using flat model - otherwise we won't get global stats
            // for all stores
            $emulationModel->startEnvironmentEmulation(0, Mage_Core_Model_App_Area::AREA_ADMINHTML);
        }
    }
}
