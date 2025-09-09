<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fuel;

class FuelController extends Controller
{
    public function index()
    {
        $fuels = Fuel::latest()->paginate(10);
        return view('dashboard.fuel.index', compact('fuels'));
    }

    public function create()
    {
        return view('dashboard.fuel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_litre' => 'required|numeric',
            'stock_litres' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Fuel::create($request->all());

        return redirect()->route('dashboard.fuel.index')->with('success', 'Fuel added successfully.');
    }

    public function show(Fuel $fuel)
    {
        return view('dashboard.fuel.show', compact('fuel'));
    }

    public function edit(Fuel $fuel)
    {
        return view('dashboard.fuel.edit', compact('fuel'));
    }

    public function update(Request $request, Fuel $fuel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_litre' => 'required|numeric',
            'stock_litres' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $fuel->update($request->all());

        return redirect()->route('dashboard.fuel.index')->with('success', 'Fuel updated successfully.');
    }

    public function destroy(Fuel $fuel)
    {
        $fuel->delete();
        return redirect()->route('fuel.index')->with('success', 'Fuel deleted successfully.');
    }
}
