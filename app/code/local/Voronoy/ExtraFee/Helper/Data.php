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

class Voronoy_ExtraFee_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_EXTRA_FEE_PAYMENT_ACTIVE           = 'extra_fee_settings/extra_fee_payment/active';
    const XML_PATH_EXTRA_FEE_PAYMENT_LABEL            = 'extra_fee_settings/extra_fee_payment/label';
    const XML_PATH_EXTRA_FEE_PAYMENT_METHODS          = 'extra_fee_settings/extra_fee_payment/payment_methods';

    const XML_PATH_EXTRA_FEE_RULE_ACTIVE              = 'extra_fee_settings/extra_fee_rule/active';
    const XML_PATH_EXTRA_FEE_RULE_LABEL               = 'extra_fee_settings/extra_fee_rule/label';

    /**
     * Fixed Extra Fee Method
     */
    const EXTRA_FEE_METHOD_FIXED = 1;

    /**
     * @return int
     */
    public function getExtraFeeMethod()
    {
        return self::EXTRA_FEE_METHOD_FIXED;
    }

    /**
     * Get Payment Method Codes with Amounts
     *
     * @return array
     */
    public function getPaymentMethodsWithExtraFee()
    {
        $payments = $this->_getPaymentsFromConfig();
        $result = array();
        foreach ($payments as $payment) {
            $result[$payment['payment_code']] = $payment['amount'];
        }
        return $result;
    }

    /**
     * Get Payments From Config
     *
     * @return array
     */
    protected function _getPaymentsFromConfig()
    {
        $shippingCosts = Mage::getStoreConfig(self::XML_PATH_EXTRA_FEE_PAYMENT_METHODS);
        if ($shippingCosts) {
            $shippingCosts = unserialize($shippingCosts);
            if (is_array($shippingCosts)) {
                return $shippingCosts;
            } else {
                return array();
            }
        }
    }

    /**
     * Check If Payment Extra Fee Enabled
     *
     * @return bool
     */
    public function isPaymentExtraFeeEnabled()
    {
        $result = (bool) Mage::getStoreConfig(self::XML_PATH_EXTRA_FEE_PAYMENT_ACTIVE);
        return $result;
    }

    /**
     * Check If Payment Extra Fee Enabled
     *
     * @return bool
     */
    public function isRuleExtraFeeEnabled()
    {
        $result = (bool) Mage::getStoreConfig(self::XML_PATH_EXTRA_FEE_RULE_ACTIVE);
        return $result;
    }

    /**
     * Get Extra Fee for Shopping Cart Rule
     *
     * @return string
     */
    public function getExtraFeeRuleLabel()
    {
        return (string) Mage::getStoreConfig(self::XML_PATH_EXTRA_FEE_RULE_LABEL);
    }

    /**
     * Get Extra Payment Fee Label
     *
     * @return string
     */
    public function getExtraFeePaymentLabel()
    {
        return (string) Mage::getStoreConfig(self::XML_PATH_EXTRA_FEE_PAYMENT_LABEL);
    }
}