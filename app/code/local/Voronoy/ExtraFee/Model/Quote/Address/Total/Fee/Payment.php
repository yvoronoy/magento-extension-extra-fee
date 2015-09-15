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

class Voronoy_ExtraFee_Model_Quote_Address_Total_Fee_Payment extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /**
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return Mage_Sales_Model_Quote_Address_Total_Abstract
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $address->setExtraFeePaymentAmount(0);
        $address->setBaseExtraFeePaymentAmount(0);

        $addressItems = $address->getAllItems();
        if (!count($addressItems)) {
            return $address;
        }

        $currentQuotePayments = $this->_getCurrentPaymentMethods($address->getQuote());

        foreach ($currentQuotePayments as $paymentCode => $feeAmount) {
            $basePrice = $feeAmount / 100 * $address->getSubtotal();
            $price     = Mage::app()->getStore()->convertPrice($basePrice);

            $this->_addAmount($price);
            $this->_addBaseAmount($basePrice);
        }

        return $address;
    }

    /**
     * Fetch Totals
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return Voronoy_ExtraFee_Model_Quote_Address_Total_Fee_Payment
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getExtraFeePaymentAmount();
        if ($amount > 0) {
            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => Mage::helper('voronoy_extrafee')->getExtraFeePaymentLabel(),
                'value' => $amount
            ));
        }
        return $this;
    }

    /**
     * Check is Apply Extra Fee for Current Payment Method
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    protected function _getCurrentPaymentMethods($quote)
    {
        $helper = Mage::helper('voronoy_extrafee');
        $payment = $quote->getPayment();
        $paymentMethodsWithExtraFee = $helper->getPaymentMethodsWithExtraFee();

        $currentPayments = array();
        if (isset($paymentMethodsWithExtraFee[$payment->getMethod()])) {
            $currentPayments[$payment->getMethod()] = $paymentMethodsWithExtraFee[$payment->getMethod()];
        }
        return $currentPayments;
    }
}
