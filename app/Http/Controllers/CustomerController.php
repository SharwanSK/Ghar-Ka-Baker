<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('bakery_id', session('bakery_id'))->get();
        return view('customers', ['customers' => $customers]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'phone'            => ['required', 'string', 'max:20'],
            'whatsapp_number'  => ['nullable', 'string', 'max:20'],
            'city'             => ['nullable', 'string'],
            'address'          => ['nullable', 'string'],
        ]);

        $validated['bakery_id'] = session('bakery_id');

        Customer::create($validated);

        return redirect('/customers');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::where('id', $id)
                    ->where('bakery_id', session('bakery_id'))
                    ->firstOrFail();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'phone'            => ['required', 'string', 'max:20'],
            'whatsapp_number'  => ['nullable', 'string', 'max:20'],
            'city'             => ['nullable', 'string'],
            'address'          => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        return redirect('/customers');
    }

    public function destroy($id)
    {
        $customer = Customer::where('id', $id)
                    ->where('bakery_id', session('bakery_id'))
                    ->firstOrFail();

        $customer->delete();

        return redirect('/customers');
    }
}