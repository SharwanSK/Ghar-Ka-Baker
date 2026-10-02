<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bakery;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
      $validated=$request->validate([
        'owner_name'=>['required','string','max:255'],
        'business_name'=>['required','string','max:255'],
        'whatsapp_number'=>['required','string','min:11',],
        'email'=>['required','string','email','unique:bakeries,email'],
        'password'=>['required','string','min:6'],

      ]);

      $bakery=Bakery::create([
      'owner_name'=>$validated['owner_name'],
      'business_name'=>$validated['business_name'],
      'whatsapp_number'=>$validated['whatsapp_number'],
      'email'=>$validated['email'],
      'password_hash'=>Hash::make($validated['password']),
    ]);
      
       return redirect('/login');
    }

}
    