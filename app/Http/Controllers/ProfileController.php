<?php

namespace App\Http\Controllers;

// use Illuminate\Http\RedirectResponse;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request) {
        $user = $request->user();
       return view('profile.edit')->with(compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request) {
        
        $validated = $request->validate([
                'name' => ['string', 'max:255'],
                'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]);
        

        $request->user()->fill($request->all());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile updated');
    }


    public function update_password(Request $request) {
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password','sometimes'],
            'password' => ['required', Password::defaults(), 'confirmed','sometimes'],
        ]);
        
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'Password updated');
    }
}