<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\BlockedWord;
use App\Models\Location;
use App\Models\Manager;
use App\Models\Proposal;
use App\Models\Setting;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/dangerous-countries', function (Request $request) {
    return Location::where('danger', '=', true)->get();
});

Route::get('/blocked-words', function (Request $request) {
    return BlockedWord::all();
});

Route::get('/settings', function (Request $request) {
    return Setting::all();
});

Route::get('/stats', function (Request $request) {
    $managerFullname = $request->input('manager_fullname', null);

    if (empty($managerFullname)) {
        return ['status' => false, 'errors' => ['manager_fullname' => 'Required parameter']];
    }

    $manager = Manager::where('fullname', '=', $managerFullname)->first();

    if (!$manager) {
        return ['status' => false, 'errors' => ['manager' => 'Not Found']];
    }

    $now = Carbon::now();

    return ['status' => true, 'data' => [
        'month' => Proposal::where('manager_id', '=', $manager->id)->where('created_at', '>=', $now->copy()->startOfMonth())->where('created_at', '<=', $now->copy()->endOfMonth())->count(),
        'today' => Proposal::where('manager_id', '=', $manager->id)->where('created_at', '>=', $now->copy()->startOfDay())->where('created_at', '<=', $now->copy()->endOfDay())->count(),
    ]];
});

Route::post('/proposal', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'location'                  => 'required|exists:' . Location::class . ',name',
        'manager_username'          => 'required|max:255',
        'manager_fullname'          => 'required|max:255',
        'url'                       => 'required|max:255',
        'title'                     => 'required',
        'description'               => 'required',
        'total_charges_amount'      => 'required|regex:/^\d+(\.\d{1,2})?$/',
        'total_charges_currency'    => 'required',
        'budget_amount'             => 'required|regex:/^\d+(\.\d{1,2})?$/',
        'budget_currency'           => 'required',
        'reply'                     => 'required'
    ]);

    if ($validator->fails()) {
        return ['status' => false, 'errors' => $validator->errors()];
    }

    $location = Location::where('name', '=', $request->input('location'))->first();
    $manager = Manager::where('fullname', '=', $request->input('manager_fullname'))->first();

    if (empty($manager)) {
        $manager = new Manager;

        $manager->username = $request->input('manager_username');
        $manager->fullname = $request->input('manager_fullname');

        if (!$manager->save()) {
            return ['status' => false, 'errors' => ['manager' => ['Not saved']]];
        }
    }

    $proposal = new Proposal;

    $proposal->location_id              = $location->id;
    $proposal->manager_id               = $manager->id;
    $proposal->url                      = $request->input('url');
    $proposal->title                    = $request->input('title');
    $proposal->description              = $request->input('description');
    $proposal->total_charges_amount     = $request->input('total_charges_amount');
    $proposal->total_charges_currency   = $request->input('total_charges_currency');
    $proposal->budget_amount            = $request->input('budget_amount');
    $proposal->budget_currency          = $request->input('budget_currency');
    $proposal->reply                    = $request->input('reply');

    if ($proposal->save()) {
        return ['status' => true, 'data' => $request->all()];
    } else {
        return ['status' => false, 'errors' => ['proposal' => ['Not saved']]];
    }
});
