<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonilController extends Controller
{
    public function index()
    {
        $personil = User::where('role', 'personil')->get();
        return view('personil.index', compact('personil'));
    }

    public function create()
    {
        return view('personil.create');
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
            'role' => 'personil',
        ]);

        return redirect()->route('personil.index')->with('success', 'Data Personil berhasil ditambahkan');
    }

    public function edit(User $personil)
    {
        return view('personil.edit', compact('personil'));
    }

    public function update(Request $request, User $personil)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $personil->id,
            'nip' => 'required|string|unique:users,nip,' . $personil->id,
            'jabatan' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'nip', 'jabatan']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $personil->update($data);

        return redirect()->route('personil.index')->with('success', 'Data Personil berhasil diperbarui');
    }

    public function destroy(User $personil)
    {
        $personil->delete();
        return redirect()->route('personil.index')->with('success', 'Data Personil berhasil dihapus');
    }
}
