<?php
/**
 * Magento Extension
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

class Voronoy_ExtraFee_Model_Sales_Order_Invoice_Total_Fee_Payment extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    /**
     * Collect Invoice Totals
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     *
     * @return Mage_Sales_Model_Order_Invoice_Total_Abstract
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        if (!Mage::helper('voronoy_extrafee')->isPaymentExtraFeeEnabled()) {
            return $this;
        }
        $invoice->setExtraFeePaymentAmount(0);
        $invoice->setBaseExtraFeePaymentAmount(0);
        if ($this->_isAmountInvoiced($invoice)) {
            return $this;
        }

        $extraFeePaymentAmount     = $invoice->getOrder()->getExtraFeePaymentAmount();
        $baseExtraFeePaymentAmount = $invoice->getOrder()->getBaseExtraFeePaymentAmount();
        if ($extraFeePaymentAmount) {
            $invoice->setExtraFeePaymentAmount($extraFeePaymentAmount);
            $invoice->setBaseExtraFeePaymentAmount($baseExtraFeePaymentAmount);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $extraFeePaymentAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseExtraFeePaymentAmount);
        }
        return $this;
    }

    /**
     * Check Amount has been invoiced
     *
     * @param $invoice
     *
     * @return bool
     */
    protected function _isAmountInvoiced($invoice)
    {
        foreach ($invoice->getOrder()->getInvoiceCollection() as $previusInvoice) {
            if ($previusInvoice->getExtraFeePayment() && !$previusInvoice->isCanceled()) {
                return true;
            }
        }

        return false;
    }
}
 