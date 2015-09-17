<?php
/**
 * Magento Extra Fee Extension
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright (c) 2015 by Yaroslav Voronoy (y.voronoy@gmail.com)
 * @license   http://www.gnu.org/licenses/
 */

/* @var Mage_Sales_Model_Resource_Setup $installer */
$installer = $this;
$tables = array(
    $installer->getTable('sales/invoice'),
);

foreach ($tables as $table) {
    $installer->getConnection()
        ->addColumn($table,
            'extra_fee_rule_description',
            array(
                'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length' => '255',
                'nullable'  => true,
                'comment' => 'Rule Extra Fee Description',
            )
        );
}
