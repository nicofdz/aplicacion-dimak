<?php

namespace App\Http\Controllers;

use App\Models\VehicleReturn;
use Illuminate\Http\Request;

class VehicleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = VehicleReturn::with(['request.user', 'request.vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.returns.index', compact('returns'));
    }
}
