<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // règles basées sur la présence du champ 'name' (tests Breeze envoient 'name')
        $rules = [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ];

        if ($request->has('name')) {
            $rules['name'] = ['required', 'string', 'max:255'];
        } else {
            // garder compat pour formulaire qui utilise first_name / last_name
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        // Mapper les champs validés vers le modèle
        if (isset($validated['name'])) {
            $fullName = trim($validated['name']);
            $parts = $fullName === '' ? [] : preg_split('/\s+/', $fullName);
            $first = $parts[0] ?? '';
            $last = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
            $user->first_name = $first;
            $user->last_name = $last;
        } else {
            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
        }

        // Gérer le changement d'email proprement
        if ($validated['email'] !== $user->email) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->email = $validated['email'];
            $user->save();
        }

        return redirect('/profile')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
