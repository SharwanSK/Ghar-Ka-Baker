<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bakery;

class SettingsController extends Controller
{
    public function index()
    {
        $bakery = Bakery::find(session('bakery_id'));

        return view('settings', [
            'bakery' => $bakery,
        ]);
    }

    public function update(Request $request)
    {
        $bakery = Bakery::find(session('bakery_id'));

        $validated = $request->validate([
            'business_name'          => ['required', 'string', 'max:150'],
            'owner_name'             => ['required', 'string', 'max:150'],
            'business_phone'         => ['nullable', 'string', 'max:20'],
            'business_city'          => ['nullable', 'string', 'max:100'],
            'business_address'       => ['nullable', 'string'],
            'jazzcash_number'        => ['nullable', 'string', 'max:20'],
            'easypaisa_number'       => ['nullable', 'string', 'max:20'],
            'bank_details'           => ['nullable', 'string'],
            'default_currency'       => ['nullable', 'string'],
            'default_order_status'   => ['nullable', 'string'],
        ]);

        // Checkbox: present in the request only when checked
        $validated['show_payment_reminders'] = $request->has('show_payment_reminders');

        $bakery->update($validated);

        return redirect('/settings')->with('status', 'Settings saved successfully.');
    }
}