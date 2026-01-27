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