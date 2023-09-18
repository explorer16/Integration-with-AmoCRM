<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidateController extends Controller
{
    function validateFormData(Request $request) 
    {
        $data = $request->validate([
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:40',
            'age' => 'required|numeric',
            'gender' => 'required',
            'telephone' => 'required|regex:/^\+\d{12,13}$/',
            'email' => 'required|email:rfc,dns'
        ]);
        
        session(['formData' => json_encode($data)]);

        return response()->json(['message' => 'success']);
    }
}
