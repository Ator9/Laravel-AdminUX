<?php

namespace App\Adminux;

use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

// Update .env APP_URL because it uses that to generate the reset password link
class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function broker()
    {
        return Password::broker('adminux');
    }

    public function showLinkRequestForm()
    {
        return view('adminux.password-email');
    }
}
