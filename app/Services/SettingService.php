<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    /**
     * Get or create settings for a user.
     */
    public function getUserSettings(User $user)
    {
        return Setting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $user->name,
                'email' => $user->email,
                'invoice_prefix' => 'INV',
                'invoice_start_number' => 1,
                'default_tax_rate' => 0,
            ]
        );
    }

    /**
     * Update company settings.
     */
    public function updateCompanySettings(User $user, array $data)
    {
        $settings = $this->getUserSettings($user);

        // Handle logo upload
        if (isset($data['logo']) && $data['logo']) {
            // Delete old logo if exists
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }

            $path = $data['logo']->store('logos', 'public');
            $data['logo'] = $path;
        } else {
            unset($data['logo']);
        }

        $settings->update($data);

        return $settings;
    }

    /**
     * Update invoice settings.
     */
    public function updateInvoiceSettings(User $user, array $data)
    {
        $settings = $this->getUserSettings($user);
        $settings->update($data);

        return $settings;
    }

    /**
     * Get a specific setting value.
     */
    public function getSetting(User $user, string $key, $default = null)
    {
        $settings = $this->getUserSettings($user);
        return $settings->$key ?? $default;
    }

    /**
     * Update a specific setting.
     */
    public function updateSetting(User $user, string $key, $value)
    {
        $settings = $this->getUserSettings($user);
        $settings->update([$key => $value]);

        return $settings;
    }

    /**
     * Generate next invoice number based on settings.
     */
    public function generateInvoiceNumber(User $user)
    {
        $settings = $this->getUserSettings($user);
        
        // Get last invoice number from user's invoices
        $lastInvoice = $user->invoices()->latest()->first();
        
        if ($lastInvoice) {
            // Extract number from last invoice
            $lastNumber = (int) filter_var($lastInvoice->invoice_number, FILTER_SANITIZE_NUMBER_INT);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = $settings->invoice_start_number;
        }

        return $settings->invoice_prefix . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get default tax rate.
     */
    public function getDefaultTaxRate(User $user)
    {
        return $this->getSetting($user, 'default_tax_rate', 0);
    }
}
