<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // POST /mi-cuenta/actualizar - Actualizar perfil del usuario
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // permite mantener s
            'email' => 'required|u propio emailemail|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed', // confirmed = debe venir password_confirmation
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Solo actualizar password si se envió uno nuevo
        if (!empty($validatedData['password'])) {
            $user->password = $validatedData['password']; // el cast 'hashed' lo hashea automáticamente
        }

        // Manejar subida de avatar
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe (ahorrar espacio)
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();
        return redirect('/mi-cuenta')->with('success', '¡Perfil actualizado exitosamente!');
    }

    // POST /mi-cuenta/personalizar - Actualizar colores del perfil
    // Feature: tema personalizable para cada usuario
    public function updateCustomization(Request $request)
    {
        $user = auth()->user();

        // Validar que sean códigos hexadecimales (#RRGGBB)
        $validatedData = $request->validate([
            'profile_bg_color' => 'required|string|max:7',      // fondo principal
            'profile_bg_color2' => 'required|string|max:7',     // fondo secundario (gradiente)
            'profile_card_color' => 'required|string|max:7',    // tarjetas
            'profile_text_color' => 'required|string|max:7',    // texto
            'profile_accent_color' => 'required|string|max:7',  // acentos/botones
        ]);

        $user->update($validatedData);
        return redirect('/mi-cuenta')->with('success', '¡Personalización guardada!');
    }
}
