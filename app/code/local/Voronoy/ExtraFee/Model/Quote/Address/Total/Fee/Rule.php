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

class Voronoy_ExtraFee_Model_Quote_Address_Total_Fee_Rule extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /**
     * Discount calculation object
     *
     * @var Mage_SalesRule_Model_Validator
     */
    protected $_calculator;

    /**
     * Initialize discount collector
     */
    public function __construct()
    {
        $this->_calculator = Mage::getSingleton('voronoy_extrafee/salesRule_validator');
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return Mage_Sales_Model_Quote_Address_Total_Abstract
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        if (!Mage::helper('voronoy_extrafee')->isRuleExtraFeeEnabled()) {
            return $this;
        }
        parent::collect($address);
        $quote = $address->getQuote();
        $store = Mage::app()->getStore($quote->getStoreId());
        $this->_calculator->reset($address);

        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }

        $this->_calculator->init($store->getWebsiteId(), $quote->getCustomerGroupId(), $quote->getCouponCode());
        $this->_calculator->initTotals($items, $address);

        $items = $this->_calculator->sortItemsByPriority($items);
        foreach ($items as $item) {
            if ($item->getParentItemId()) {
                continue;
            }
            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                foreach ($item->getChildren() as $child) {
                    $this->_calculator->process($child);
                    $this->_addAmount($child->getExtraFeeRuleAmount());
                    $this->_addBaseAmount($child->getBaseExtraFeeRuleAmount());
                }
            } else {
                $this->_calculator->process($item);
                $this->_addAmount($item->getExtraFeeRuleAmount());
                $this->_addBaseAmount($item->getBaseExtraFeeRuleAmount());
            }
        }

        $this->_calculator->prepareDescription($address);
    }

    /**
     * Fetch Totals
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return Voronoy_ExtraFee_Model_Quote_Address_Total_Fee_Rule
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if (!Mage::helper('voronoy_extrafee')->isRuleExtraFeeEnabled()) {
            return $this;
        }
        $amount = $address->getExtraFeeRuleAmount();
        if ($address->getExtraFeeRuleDescription()) {
            $discountLabel = Mage::helper('voronoy_extrafee')->__('%s (%s)',
                Mage::helper('voronoy_extrafee')->getExtraFeeRuleLabel(), $address->getExtraFeeRuleDescription());
        } else {
            $discountLabel = Mage::helper('voronoy_extrafee')->getExtraFeeRuleLabel();
        }

        if ($amount > 0) {
            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => $discountLabel,
                'value' => $amount
            ));
        }
        return $this;
    }
}
