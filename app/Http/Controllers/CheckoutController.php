<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $token)
    {
        //dd($request->headers);
        //dd($token);
        return $this->getUser($token);
    }

    private function getUser($token){
        return User::find($token);
    }
}
