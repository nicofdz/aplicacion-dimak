<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'assignments.user']);

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('internal_code', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filtro Categoría
        if ($request->filled('category_id')) {
            $query->where('asset_category_id', $request->category_id);
        }

        // Filtro Estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->latest()->paginate(15)->withQueryString();
        $categories = AssetCategory::orderBy('name')->get();

        return view('assets.index', compact('assets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No se usa, es por modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'internal_code' => 'required|string|unique:assets,internal_code',
            'name' => 'required|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'status' => 'required|in:available,assigned,maintenance,written_off',
            'location' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
            'cost' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:5120', // 5MB
            'observations' => 'nullable|string',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('assets', 'public');
            $data['image_path'] = $path;
        }

        // Si no mandan código de barras, usamos el interno por defecto si se desea, 
        // o generamos uno. Por ahora dejemoslo null si no se envía.
        if ($request->filled('barcode')) {
            $data['barcode'] = $request->barcode;
        }

        Asset::create($data);

        return redirect()->route('assets.index')->with('success', 'Activo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        //
    }

    public function myAssignments()
    {
        // TODO: Implement
        return view('assets.my_assignments', ['assignments' => []]);
    }

    public function dashboard()
    {
        // TODO: Implement
        return view('assets.dashboard');
    }
}
