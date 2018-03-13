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
 * @package     Mage_Admin
 * @copyright   Copyright (c) 2015 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$tablePermissionVariable = $installer->getTable('admin/permission_variable');
$tablePermissionBlock = $installer->getTable('admin/permission_block');

$installer->run("
CREATE TABLE `{$tablePermissionVariable}` (
  `variable_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Variable ID',
  `variable_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Config Path',
  `is_allowed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Mark that config can be processed by filters',
  PRIMARY KEY (`variable_id`,`variable_name`),
  UNIQUE KEY `UNQ_PERMISSION_VARIABLE_VARIABLE_NAME` (`variable_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System variables that can be processed via content filter';
");

$installer->run("
INSERT INTO `{$tablePermissionVariable}`
  (`variable_name`, `is_allowed`)
VALUES
  ('trans_email/ident_support/name', 1),
  ('trans_email/ident_support/email', 1),
  ('web/unsecure/base_url', 1),
  ('web/secure/base_url', 1),
  ('trans_email/ident_general/name', 1),
  ('trans_email/ident_general/email', 1),
  ('trans_email/ident_sales/name', 1),
  ('trans_email/ident_sales/email', 1),
  ('trans_email/ident_custom1/name', 1),
  ('trans_email/ident_custom1/email', 1),
  ('trans_email/ident_custom2/name', 1),
  ('trans_email/ident_custom2/email', 1),
  ('general/store_information/name', 1),
  ('general/store_information/phone', 1),
  ('general/store_information/address', 1);
");

$installer->run("
CREATE TABLE `{$tablePermissionBlock}` (
  `block_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Block ID',
  `block_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Block Name',
  `is_allowed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Mark that block can be processed by filters',
  PRIMARY KEY (`block_id`),
  UNIQUE KEY `UNQ_PERMISSION_BLOCK_BLOCK_NAME` (`block_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System blocks that can be processed via content filter';
");

$installer->run("
INSERT INTO `{$tablePermissionBlock}`
  (`block_name`, `is_allowed`)
VALUES
  ('core/template', 1),
  ('catalog/product_new', 1),
  ('enterprise_catalogevent/event_lister', 1);
");

$installer->endSetup();
