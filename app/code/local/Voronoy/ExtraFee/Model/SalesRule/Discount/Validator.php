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

class Voronoy_ExtraFee_Model_SalesRule_Discount_Validator extends Mage_SalesRule_Model_Validator
{
    /**
     * Check if we can process rule
     *
     * @param Mage_SalesRule_Model_Rule $rule
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return bool
     */
    protected function _canProcessRule($rule, $address)
    {
        if (!Mage::helper('voronoy_extrafee')->isRuleExtraFeeEnabled()) {
            return parent::_canProcessRule($rule, $address);
        }
        if ($rule->getDiscountAmount() == 0) {
            return false;
        }
        return parent::_canProcessRule($rule, $address);
    }
}