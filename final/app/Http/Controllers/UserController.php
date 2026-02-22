<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Update user profile (name, email, password)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = $validatedData['password']; // cast 'hashed' handles it
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect('/mi-cuenta')->with('success', '¡Perfil actualizado exitosamente!');
    }

    /**
     * Update user profile customization (colors)
     */
    public function updateCustomization(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'profile_bg_color' => 'required|string|max:7',
            'profile_bg_color2' => 'required|string|max:7',
            'profile_card_color' => 'required|string|max:7',
            'profile_text_color' => 'required|string|max:7',
            'profile_accent_color' => 'required|string|max:7',
        ]);

        $user->update($validatedData);

        return redirect('/mi-cuenta')->with('success', '¡Personalización guardada!');
    }
}
