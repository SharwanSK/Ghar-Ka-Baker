<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bakery;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    public function login(){
        return view('login');
    }

    public function authenticate(Request $request){
          $bakery=Bakery::where('email',$request->email)->first();
        if (!$bakery) {
            return back()->withErrors(['email'=>'this email not registered']);
        }

        
        if (!Hash::check($request->password,$bakery->password_hash)) {
            return back()->withErrors(['password'=>'wrong password']);
        }

        session(['bakery_id'=>$bakery->id]);
        return redirect('/dashboard');



    }

     public function logout(){
            session()->forget('bakery_id');
            return redirect('/login');
        }
  
    
}
