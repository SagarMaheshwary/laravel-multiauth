<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    /**
     * Create a controller instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify','resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user('admin')->hasVerifiedEmail()
            ? redirect()->route('admin.home')
            : view('auth.verify',[
                'resendRoute' => 'admin.verification.resend',
            ]);
    }

    /**
     * Verfy the user email.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        if ($request->route('id') != $request->user('admin')->getKey()) {
            //id value doesn't match.
            return redirect()
                ->route('admin.verification.notice')
                ->with('error','Invalid user!');
        }

        if ($request->user('admin')->hasVerifiedEmail()) {
            return redirect()
                ->route('admin.home');
        }

        $request->user('admin')->markEmailAsVerified();

        return redirect()
            ->route('admin.home')
            ->with('status','Thank you for verifying your email!');
    }

    /**
     * Resend the verification email.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($request->user('admin')->hasVerifiedEmail()) {
            return redirect()->route('admin.home');
        }

        $request->user('admin')->sendEmailVerificationNotification();

        return redirect()
            ->back()
            ->with('status','We have sent you a verification email!');
    }

}
