<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kepala_madrasah,guru_mapel,guru_bk,wali_kelas',
            'nip_nik' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nip_nik' => $request->nip_nik,
        ]);

        return back()->with('success', 'Pengguna Baru Berhasil Ditambahkan!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', "Password untuk {$user->name} Berhasil Di-reset!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!');
        }

        $user->delete();
        return back()->with('success', 'Pengguna Berhasil Dihapus!');
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,kepala_madrasah,guru_mapel,guru_bk,wali_kelas',
            'nip_nik' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->nip_nik = $request->nip_nik;
    
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
    
        $user->save();
    
        return back()->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }
}