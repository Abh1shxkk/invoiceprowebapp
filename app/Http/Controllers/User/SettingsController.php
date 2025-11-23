<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = $this->settingService->getUserSettings(auth()->user());
        
        return view('user.settings.index', compact('settings'));
    }

    /**
     * Update company settings.
     */
    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
        ]);

        $this->settingService->updateCompanySettings(auth()->user(), $validated);

        return redirect()->route('user.settings.index')
            ->with('success', 'Company settings updated successfully.');
    }

    /**
     * Update invoice settings.
     */
    public function updateInvoice(Request $request)
    {
        $validated = $request->validate([
            'invoice_prefix' => ['required', 'string', 'max:10'],
            'invoice_start_number' => ['required', 'integer', 'min:1'],
            'default_tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'payment_terms' => ['nullable', 'string'],
            'invoice_footer' => ['nullable', 'string'],
        ]);

        $this->settingService->updateInvoiceSettings(auth()->user(), $validated);

        return redirect()->route('user.settings.index')
            ->with('success', 'Invoice settings updated successfully.');
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                \Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->route('user.settings.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.settings.index')
            ->with('success', 'Password updated successfully.');
    }
}
