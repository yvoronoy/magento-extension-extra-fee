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
    const XML_PATH_EXTRA_FEE_PAYMENT_SETTINGS = 'extra_fee_settings/extra_fee_payment/payment_methods';

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

    public function _getPaymentsFromConfig()
    {
        $shippingCosts = Mage::getStoreConfig(self::XML_PATH_EXTRA_FEE_PAYMENT_SETTINGS);
        if ($shippingCosts) {
            $shippingCosts = unserialize($shippingCosts);
            if (is_array($shippingCosts)) {
                return $shippingCosts;
            } else {
                return array();
            }
        }
    }

    public function getExtraFeeRuleLabel()
    {
        return "Extra Fee Rule";
    }

    public function getExtraFeePaymentLabel()
    {
        return "Payment Extra Fee";
    }
}