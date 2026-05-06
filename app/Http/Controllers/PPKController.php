<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PPKController extends Controller
{
    public function index()
    {
        $ppks = User::where('role', 'ppk')->get();
        return view('ppk.index', compact('ppks'));
    }

    public function create()
    {
        return view('ppk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'required|string|unique:users',
            'jabatan' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'password' => Hash::make($request->password),
            'role' => 'ppk',
        ]);

        return redirect()->route('personil.index')->with('success', 'Data Tenaga PPK berhasil ditambahkan');
    }

    public function edit(User $ppk)
    {
        return view('ppk.edit', compact('ppk'));
    }

    public function update(Request $request, User $ppk)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $ppk->id,
            'nip' => 'required|string|unique:users,nip,' . $ppk->id,
            'jabatan' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'nip', 'jabatan']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $ppk->update($data);

        return redirect()->route('personil.index')->with('success', 'Data Tenaga PPK berhasil diperbarui');
    }

    public function destroy(User $ppk)
    {
        $ppk->delete();
        return redirect()->route('personil.index')->with('success', 'Data Tenaga PPK berhasil dihapus');
    }
}