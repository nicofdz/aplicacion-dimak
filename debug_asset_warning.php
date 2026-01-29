<?php

$asset = \App\Models\Asset::where('codigo_interno', 'ACT-0001')->with(['assignments', 'maintenances'])->first();

if (!$asset) {
    echo "Asset not found\n";
    exit;
}

echo "Asset: {$asset->nombre} ({$asset->codigo_interno})\n";
echo "Estado: {$asset->estado}\n";

$lastAssignment = $asset->assignments->sortByDesc('created_at')->first();

if (!$lastAssignment) {
    echo "No assignments found.\n";
} else {
    echo "Last Assignment ID: {$lastAssignment->id}\n";
    echo "Created At: {$lastAssignment->created_at}\n";
    echo "Estado Devolucion: [{$lastAssignment->estado_devolucion}]\n";
    echo "Fecha Devolucion: {$lastAssignment->fecha_devolucion}\n";

    $checkStatus = in_array($lastAssignment->estado_devolucion, ['bad', 'damaged', 'regular']);
    echo "Status Check (bad/damaged/regular): " . ($checkStatus ? 'TRUE' : 'FALSE') . "\n";

    if ($checkStatus) {
        $returnDate = $lastAssignment->fecha_devolucion ?? $lastAssignment->updated_at;
        echo "Return Date Base: {$returnDate}\n";

        $rDate = \Carbon\Carbon::parse($returnDate);
        echo "Return Date Carbon: {$rDate}\n";

        echo "Checking Maintenances ({$asset->maintenances->count()} total):\n";

        $hasRecentMaintenance = $asset->maintenances
            ->where('fecha_termino', '!=', null)
            ->filter(function ($maintenance) use ($rDate) {
                $mDate = \Carbon\Carbon::parse($maintenance->fecha_termino);
                $isRecent = $mDate->gte($rDate->startOfDay());
                echo " - Maintenance {$maintenance->id}: Termino {$mDate} vs Return {$rDate} (StartOfDay: {$rDate->copy()->startOfDay()}) => " . ($isRecent ? 'RECENT' : 'OLD') . "\n";
                return $isRecent;
            })
            ->count() > 0;

        echo "Has Recent Maintenance: " . ($hasRecentMaintenance ? 'TRUE' : 'FALSE') . "\n";

        if (!$hasRecentMaintenance) {
            echo "RESULT: WARNING SHOULD BE VISIBLE\n";
        } else {
            echo "RESULT: WARNING HIDDEN due to recent maintenance\n";
        }
    } else {
        echo "RESULT: WARNING HIDDEN due to status not being bad/damaged/regular\n";
    }
}
