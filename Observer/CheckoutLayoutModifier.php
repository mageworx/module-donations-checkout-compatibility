<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\DonationsCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class CheckoutLayoutModifier
 *
 * Move donations elements on the MageWorx_Checkout page;
 * Change templates;
 */
class CheckoutLayoutModifier implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var \MageWorx\Checkout\Api\LayoutModiferAccess $subject */
        $subject = $observer->getSubject();
        /** @var array $jsLayout */
        $jsLayout = &$subject->getJsLayout();

        $nameInLayout = 'mageworx-donation-form-container';
        // Copy element
        $originalElement = $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']
        ['itemsBefore']['children'][$nameInLayout];

        // Remove original element from layout
        unset(
            $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']
            ['itemsBefore']['children'][$nameInLayout]
        );

        $originalElement['config']['template'] = 'MageWorx_DonationsCheckout/summary/additional-inputs/mageworx-donation-form';

        // @TODO: Update child components here (after giving them name)
        $this->updateCharitySelect($originalElement);
        $this->updatePredefinedDonationSelect($originalElement);
        $this->updateDonationsManualInput($originalElement);
        $this->updateDonationsRoundUpCheckbox($originalElement);

        $jsLayout['components']['checkout']['children']['sidebar']['children']['additionalInputs']['children'][$nameInLayout] =
            $originalElement;

        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['totals']
        ['children']['mageworx_donation']['config']['template'] = 'MageWorx_DonationsCheckout/summary/totals/mageworx-donation';
    }

    /**
     * Updates the select charity component (if exist)
     *
     * @param array $originalElement
     * @return array
     */
    private function updateCharitySelect(array &$originalElement): array
    {
        if (!empty(
        $originalElement['children']['mageworx-donation-form-fieldset']['children']['select-charity']
        )) {
            $selectCharity = &$originalElement['children']['mageworx-donation-form-fieldset']
            ['children']['select-charity'];

            $selectCharity['config']['template'] = 'MageWorx_DonationsCheckout/form/select-charity-field';
            $selectCharity['config']['elementTmpl'] = 'MageWorx_DonationsCheckout/form/element/select';
        }

        return $originalElement;
    }

    /**
     * Updates the predefined donations amount component (if exist)
     *
     * @param array $originalElement
     * @return array
     */
    private function updatePredefinedDonationSelect(array &$originalElement): array
    {
        if (!empty(
        $originalElement['children']['mageworx-donation-form-fieldset']['children']['predefined-donations-select']
        )) {
            $component = &$originalElement['children']['mageworx-donation-form-fieldset']
            ['children']['predefined-donations-select'];

            $component['config']['template'] = 'MageWorx_DonationsCheckout/form/predefined-select';
            $component['config']['elementTmpl'] = 'MageWorx_DonationsCheckout/form/element/select';
        }

        return $originalElement;
    }

    /**
     * Updates the manual donations amount input component (if exist)
     *
     * @param array $originalElement
     * @return array
     */
    private function updateDonationsManualInput(array &$originalElement): array
    {
        if (!empty(
        $originalElement['children']['mageworx-donation-form-fieldset']['children']['donations-manual-input']
        )) {
            $component = &$originalElement['children']['mageworx-donation-form-fieldset']
            ['children']['donations-manual-input'];

            $component['config']['template'] = 'MageWorx_DonationsCheckout/form/manual-amount-input';
            $component['config']['elementTmpl'] = 'MageWorx_DonationsCheckout/form/element/input';
        }

        return $originalElement;
    }

    /**
     * Updates the roundup donation checkbox component (if exist)
     *
     * @param array $originalElement
     * @return array
     */
    private function updateDonationsRoundUpCheckbox(array &$originalElement): array
    {
        if (!empty(
        $originalElement['children']['mageworx-donation-round-up-fieldset']['children']['donations-roundup-checkbox']
        )) {
            $component = &$originalElement['children']['mageworx-donation-round-up-fieldset']
            ['children']['donations-roundup-checkbox'];

            $component['config']['template'] = 'MageWorx_DonationsCheckout/form/round-up-checkbox';
        }

        return $originalElement;
    }
}
