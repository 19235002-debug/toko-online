<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.user', compact('users'));
    }

    public function create()
    {
        return view('user.user-add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'no_hp' => 'nullable|max:15',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Upload foto jika ada
        if ($request->hasFile('user_image')) {
            $validated['user_image'] = $request->file('user_image')->store('user_images', 'public');
        }

        User::create($validated);

        return redirect('/profile')->with('success', 'User has been created successfully.');
    }

    public function edit(User $user)
    {
        return view('user.user-edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => 'nullable|min:6',
            'role' => 'required',
            'no_hp' => 'nullable|max:15',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Proses password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Upload foto baru jika ada
        if ($request->hasFile('user_image')) {
            // Hapus gambar lama jika ada
            if ($user->user_image && Storage::disk('public')->exists($user->user_image)) {
                Storage::disk('public')->delete($user->user_image);
            }

            // Simpan gambar baru
            $validated['user_image'] = $request->file('user_image')->store('user_images', 'public');
        }

        $user->update($validated);

        return redirect('/profile')->with('success', 'User has been updated successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = \App\Models\User::find(auth()->id());

        $validated = $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => 'nullable|min:6|confirmed',
            'no_hp' => 'nullable|regex:/^[0-9]+$/|max:15',
            'alamat' => 'nullable|string|max:1000',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'user_image.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'] ?? $user->no_hp;
        $user->alamat = $validated['alamat'] ?? $user->alamat;

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Upload foto baru jika ada
        if ($request->hasFile('user_image')) {
            // Hapus foto lama jika ada
            if ($user->user_image && Storage::disk('public')->exists($user->user_image)) {
                Storage::disk('public')->delete($user->user_image);
            }
            $user->user_image = $request->file('user_image')->store('user_images', 'public');
        }

        $user->save();

        Alert::success('Berhasil', 'Profil Anda telah berhasil diperbarui.');
        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Hapus gambar jika ada
        if ($user->user_image && Storage::disk('public')->exists($user->user_image)) {
            Storage::disk('public')->delete($user->user_image);
        }

        $user->delete();

        return redirect('/profile')->with('success', 'User has been deleted successfully.');
    }
}
