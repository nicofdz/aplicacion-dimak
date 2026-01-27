<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AssetAssignment;
use App\Models\User;
use App\Models\Worker;
use Picqer\Barcode\BarcodeGeneratorPNG;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'assignments.user', 'assignments.worker']);

        // Filtro de Búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('codigo_interno', 'like', "%{$search}%")
                    ->orWhere('nombre', 'like', "%{$search}%")
                    ->orWhere('marca', 'like', "%{$search}%")
                    ->orWhere('modelo', 'like', "%{$search}%")
                    ->orWhere('codigo_barra', 'like', "%{$search}%");
            });
        }

        // Filtro de Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro de Categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->input('categoria'));
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = AssetCategory::all();
        $users = User::all(); // Para el modal de asignación
        $workers = Worker::orderBy('nombre')->get(); // Para el modal de asignación

        return view('assets.index', compact('assets', 'categories', 'users', 'workers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Limpiar formato de moneda
        if ($request->has('valor_referencial')) {
            $request->merge([
                'valor_referencial' => str_replace('.', '', $request->input('valor_referencial')),
            ]);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:asset_categories,id',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'numero_serie' => 'nullable|string|max:255',
            'estado' => 'required|in:available,assigned,maintenance,written_off',
            'ubicacion' => 'nullable|string|max:255',
            'fecha_adquisicion' => 'nullable|date',
            'valor_referencial' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        $data = $request->except(['foto']);

        // Guardar foto si existe
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('assets', 'public');
            $data['foto_path'] = $path;
        }

        Asset::create($data);

        return redirect()->route('assets.index')->with('success', 'Activo creado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        // Limpiar formato de moneda
        if ($request->has('valor_referencial')) {
            $request->merge([
                'valor_referencial' => str_replace('.', '', $request->input('valor_referencial')),
            ]);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:asset_categories,id',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'numero_serie' => 'nullable|string|max:255',
            'estado' => 'required|in:available,assigned,maintenance,written_off',
            'ubicacion' => 'nullable|string|max:255',
            'fecha_adquisicion' => 'nullable|date',
            'valor_referencial' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        $data = $request->except(['foto']);

        // Actualizar foto si se subió una nueva
        if ($request->hasFile('foto')) {
            // Eliminar foto antigua si existe
            if ($asset->foto_path) {
                Storage::disk('public')->delete($asset->foto_path);
            }
            $path = $request->file('foto')->store('assets', 'public');
            $data['foto_path'] = $path;
        }

        $asset->update($data);

        return redirect()->route('assets.index')->with('success', 'Activo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Activo enviado a la papelera.');
    }
    /**
     * Display a listing of trashed resources.
     */
    public function trash()
    {
        $assets = Asset::onlyTrashed()->with('category')->orderBy('deleted_at', 'desc')->get();
        return view('assets.trash', compact('assets'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $asset = Asset::withTrashed()->findOrFail($id);
        $asset->restore();
        return redirect()->route('assets.trash')->with('success', 'Activo restaurado exitosamente.');
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $asset = Asset::withTrashed()->findOrFail($id);

        // Eliminar foto si existe
        if ($asset->foto_path) {
            Storage::disk('public')->delete($asset->foto_path);
        }

        $asset->forceDelete();
        return redirect()->route('assets.trash')->with('success', 'Activo eliminado permanentemente.');
    }
    public function assign(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        if ($asset->estado !== 'available') {
            return back()->with('error', 'El activo no está disponible para asignación.');
        }

        // Validaciones dinámicas
        $rules = [
            'tipo_asignacion' => 'required|in:user,worker',
            'fecha_entrega' => 'required|date',
            'fecha_estimada_devolucion' => 'nullable|date|after_or_equal:fecha_entrega',
            'observaciones' => 'nullable|string',
        ];

        if ($request->tipo_asignacion === 'user') {
            $rules['usuario_id'] = 'required|exists:users,id';
        } else {
            // Si es trabajador
            if ($request->has('is_new_worker') && $request->is_new_worker == 1) {
                // Si marcó "Nuevo Trabajador", validamos los campos de texto
                $rules['trabajador_nombre'] = 'required|string|max:255';
                $rules['trabajador_rut'] = 'required|string|max:20';
                $rules['trabajador_departamento'] = 'nullable|string|max:255';
                $rules['trabajador_cargo'] = 'nullable|string|max:255';
            } else {
                // Si NO marcó nuevo, debe haber seleccionado uno
                $rules['worker_id_select'] = 'required|exists:workers,id';
            }
        }

        $request->validate($rules);

        $workerId = null;
        $workerData = null;

        if ($request->tipo_asignacion === 'worker') {
            if ($request->has('is_new_worker') && $request->is_new_worker == 1) {
                // Crear o actualizar (si el RUT ya existía, actualizamos datos)
                $worker = Worker::updateOrCreate(
                    ['rut' => $request->trabajador_rut],
                    [
                        'nombre' => $request->trabajador_nombre,
                        'departamento' => $request->trabajador_departamento,
                        'cargo' => $request->trabajador_cargo
                    ]
                );
                $workerId = $worker->id;
                $workerData = $worker; // Para llenar campos de redundancia si se desea
            } else {
                // Usar existente
                $workerId = $request->worker_id_select;
                $workerData = Worker::find($workerId);
            }
        }

        AssetAssignment::create([
            'activo_id' => $asset->id,
            'usuario_id' => $request->tipo_asignacion === 'user' ? $request->usuario_id : null,
            'worker_id' => $workerId,
            'trabajador_nombre' => $workerData ? $workerData->nombre : null,
            'trabajador_rut' => $workerData ? $workerData->rut : null,
            'trabajador_departamento' => $workerData ? $workerData->departamento : null,
            'trabajador_cargo' => $workerData ? $workerData->cargo : null,
            'fecha_entrega' => $request->fecha_entrega,
            'fecha_estimada_devolucion' => $request->fecha_estimada_devolucion,
            'estado_entrega' => 'good',
            'observaciones' => $request->observaciones,
        ]);

        $asset->update(['estado' => 'assigned']);

        return redirect()->route('assets.index')->with('success', 'Activo asignado correctamente.');
    }

    public function updateAssignment(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $rules = [
            'fecha_entrega' => 'required|date',
            'fecha_estimada_devolucion' => 'nullable|date|after_or_equal:fecha_entrega',
            'observaciones' => 'nullable|string',
        ];

        $request->validate($rules);

        // Buscar la asignación activa
        $assignment = $asset->active_assignment;

        if (!$assignment) {
            return back()->with('error', 'No se encontró una asignación activa para este activo.');
        }

        $assignment->update([
            'fecha_entrega' => $request->fecha_entrega,
            'fecha_estimada_devolucion' => $request->fecha_estimada_devolucion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('assets.index')->with('success', 'Asignación actualizada correctamente.');
    }

    /**
     * Download barcode PDF.
     */
    public function downloadBarcode($id)
    {
        $asset = Asset::withTrashed()->findOrFail($id);

        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($asset->codigo_barra, $generator::TYPE_CODE_128));

        $pdf = Pdf::loadView('assets.barcode', compact('asset', 'barcode'));

        // Configurar tamaño de papel para impresora de etiquetas (e.g. 50mm x 30mm)
        // O A4 con una sola etiqueta si se prefiere. 
        // Vamos a usar un tamaño personalizado pequeño para etiqueta individual
        $pdf->setPaper([0, 0, 200, 120], 'landscape'); // aprox 70mm x 42mm

        return $pdf->download(\Illuminate\Support\Str::slug($asset->nombre . ' ' . ($asset->marca ?? '') . ' ' . $asset->codigo_interno) . '.pdf');
    }
    public function cancelAssignment(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        $assignment = $asset->activeAssignment;

        if ($assignment) {
            $request->validate([
                'estado_devolucion' => 'required|string',
                'comentarios_devolucion' => 'nullable|string',
            ]);

            $assignment->update([
                'fecha_devolucion' => now(),
                'estado_devolucion' => $request->estado_devolucion,
                'comentarios_devolucion' => $request->comentarios_devolucion,
            ]);
        }

        $asset->update(['estado' => 'available']);

        return back()->with('success', 'Asignación terminada correctamente.');
    }

    public function history(Request $request, $id)
    {
        $asset = Asset::withTrashed()->findOrFail($id);

        $query = $asset->assignments()
            ->with(['user', 'worker'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('fecha_entrega', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('fecha_entrega', '<=', $request->end_date);
        }

        $assignments = $query->get();

        return view('assets.history', compact('asset', 'assignments'));
    }

    public function downloadHistoryPdf(Request $request, $id)
    {
        $asset = Asset::withTrashed()->findOrFail($id);

        $query = $asset->assignments()
            ->with(['user', 'worker'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('fecha_entrega', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('fecha_entrega', '<=', $request->end_date);
        }

        $assignments = $query->get();

        $pdf = Pdf::loadView('assets.history-pdf', compact('asset', 'assignments'));

        return $pdf->download('historial-' . $asset->codigo_interno . '-' . now()->format('dmY-His') . '.pdf');
    }
}
