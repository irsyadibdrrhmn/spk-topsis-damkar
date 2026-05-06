<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::all();
        return view('criteria.index', compact('criteria'));
    }

    public function create()
    {
        return view('criteria.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'code' => 'required|unique:criteria',
        'name' => 'required',
        'type' => 'required|in:benefit', // Hanya benefit
        'weight' => 'required|numeric|min:0|max:1',
        'description' => 'nullable|string',
    ]);

    Criteria::create($request->all());
    return redirect()->route('criteria.index')->with('success', 'Kriteria berhasil ditambahkan');
}

    public function edit(Criteria $criterion)
    {
        return view('criteria.edit', compact('criterion'));
    }

    public function update(Request $request, Criteria $criterion)
{
    $request->validate([
        'code' => 'required|unique:criteria,code,' . $criterion->id,
        'name' => 'required',
        'type' => 'required|in:benefit', // Hanya benefit
        'weight' => 'required|numeric|min:0|max:1',
        'description' => 'nullable|string',
    ]);

    $criterion->update($request->all());
    return redirect()->route('criteria.index')->with('success', 'Kriteria berhasil diperbarui');
}

    public function destroy(Criteria $criterion)
    {
        $criterion->delete();
        return redirect()->route('criteria.index')->with('success', 'Kriteria berhasil dihapus');
    }
}