<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request){
        if(auth()->attempt($request->only('email','password'))){
            return redirect('/');
        }
        return redirect('login')->with('message','Unauthorized');
    }
}
