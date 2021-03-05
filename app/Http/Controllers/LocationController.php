<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::orderBy('danger', 'desc')->orderBy('name', 'asc')->get();

        return view('locations', ['locations' => $locations]);
    }

    public function update(Request $request, Location $location)
    {
        $danger = empty($request->input('danger')) ? false : true;

        $location->danger = $danger;
        $location->save();

        return redirect(route('location.index'));
    }
}
