<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // validate the form data
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        // attempt login
        if (auth()->guard('student')->attempt(['nisn' => $request->nisn, 'password' => $request->password])) {
            // redirect to dashboard
            return redirect()->route('student.dashboard');
        }

        return redirect()->back()->with('error', 'NISN atau Password salah');
    }
}
