<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        DB::table('locations')->insert([
            'name' => $request->name,
            'coordinates' => DB::raw("ST_GeomFromText('POINT({$request->longitude} {$request->latitude})')")
        ]);

        return response()->json(['message' => 'Location saved successfully!']);
    }

    public function index()
    {
        $locations = DB::table('locations')->selectRaw("id, name, ST_AsText(coordinates) as coordinates")->get();

        return response()->json($locations);
    }
}
