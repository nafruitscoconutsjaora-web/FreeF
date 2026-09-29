<?php
namespace App\Services;

use App\Core\Database;

class CurrencyService {
    /**
     * Convert provider cost to customer price based on exchange rate and markup rules
     */
    public function calculateCustomerPrice(
        float $providerCost, 
        string $providerCurrency = 'USD', 
        ?float $customExchangeRate = null, 
        ?float $customMarkupPercent = null, 
        float $fixedMarkup = 0.00
    ): float {
        // Fetch exchange rate from system settings if not specified
        if ($customExchangeRate === null) {
            $setting = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'usd_inr_exchange_rate'");
            $exchangeRate = $setting ? (float)$setting['setting_value'] : 85.50;
        } else {
            $exchangeRate = $customExchangeRate;
        }

        // Fetch default markup if not specified
        if ($customMarkupPercent === null) {
            $setting = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'default_markup_percent'");
            $markupPercent = $setting ? (float)$setting['setting_value'] : 15.00;
        } else {
            $markupPercent = $customMarkupPercent;
        }

        // Step 1: Currency conversion (e.g. USD to INR)
        $costInStoreCurrency = ($providerCurrency === 'USD') ? ($providerCost * $exchangeRate) : $providerCost;

        // Step 2: Apply markup percentage
        $withPercentageMarkup = $costInStoreCurrency * (1 + ($markupPercent / 100));

        // Step 3: Apply fixed markup
        $finalUnrounded = $withPercentageMarkup + $fixedMarkup;

        // Step 4: Rounding up to neat consumer pricing (e.g. ceil or 2 decimals)
        return round($finalUnrounded, 2);
    }
}
