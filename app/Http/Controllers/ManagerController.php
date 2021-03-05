<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Manager;
use App\Models\Proposal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{
    public static function getDates(Request $request)
    {
        $now = Carbon::now();

        $fromValidator = Validator::make($request->all(), ['from' => 'required|date']);
        $from = $fromValidator->fails() ? $now->copy()->startOfMonth() : Carbon::parse($request->input('from'))->startOfDay();

        $toValidator = Validator::make($request->all(), ['to' => 'required|date']);
        $to = $toValidator->fails() ? $from->copy()->endOfMonth() : Carbon::parse($request->input('to'))->endOfDay();

        return [$from, $to];
    }

    public static function getStats($from, $to)
    {
        $now = Carbon::now();

        $proposalsThisMonth = Proposal::where('created_at', '>=', $now->copy()->startOfMonth())->where('created_at', '<=', $now->copy()->endOfMonth())->count();
        $proposalsToday = Proposal::where('created_at', '>=', $now->copy()->startOfDay())->where('created_at', '<=', $now->copy()->endOfDay())->count();

        $mostProposalsThisMonth = Manager::withCount([
            'proposals' => function ($query) use ($now) {
                $query->where('created_at', '>=', $now->copy()->startOfMonth())->where('created_at', '<=', $now->copy()->endOfMonth());
            }
        ])->orderBy('proposals_count', 'desc')->first();
        $mostProposalsToday = Manager::withCount([
            'proposals' => function ($query) use ($now) {
                $query->where('created_at', '>=', $now->copy()->startOfDay())->where('created_at', '<=', $now->copy()->endOfDay());
            }
        ])->orderBy('proposals_count', 'desc')->first();

        $totalProposals = Proposal::where('created_at', '>=', $from)->where('created_at', '<=', $to)->count();
        $mostCommonCountry = Location::withCount([
            'proposals' => function ($query) use ($from, $to) {
                $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
            }
        ])->orderBy('proposals_count', 'desc')->first();
        $mostCommonDangerousCountry = Location::where('danger', '=', true)->withCount([
            'proposals' => function ($query) use ($from, $to) {
                $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
            }
        ])->orderBy('proposals_count', 'desc')->first();

        return [
            'proposalsThisMonth' => $proposalsThisMonth,
            'proposalsToday' => $proposalsToday,
            'mostProposalsThisMonth' => $mostProposalsThisMonth,
            'mostProposalsToday' => $mostProposalsToday,
            'totalProposals' => $totalProposals,
            'mostCommonCountry' => $mostCommonCountry,
            'mostCommonDangerousCountry' => $mostCommonDangerousCountry
        ];
    }

    public static function getDateStats(Manager $manager, Carbon $from, Carbon $to)
    {
        $stats = [];

        $start = $from->copy();
        $end = $to->copy();

        do {
            $year = $start->format('Y');
            $month = $start->format('m');
            $day = $start->format('d');

            if (!isset($stats[$year])) {
                $stats[$year] = [];
            }

            if (!isset($stats[$year][$month])) {
                $stats[$year][$month] = [
                    'month' => $start->format('F'),
                    'data' => []
                ];
            }

            if (!isset($stats[$year][$month][$day])) {
                $stats[$year][$month]['data'][$day] = 0;
            }

            $start->addDays(1);
        } while ($start->lte($end));

        $proposals = Proposal::where('manager_id', '=', $manager->id)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();

        foreach ($proposals as $proposal) {
            $stats[$proposal->created_at->format('Y')][$proposal->created_at->format('m')]['data'][$proposal->created_at->format('d')] += 1;
        }

        return [$stats, $proposals->count()];
    }

    public function index(Request $request)
    {
        list($from, $to) = self::getDates($request);

        $managers = Manager::withCount([
            'proposals' => function ($query) use ($from, $to) {
                $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
            }
        ])->orderBy('proposals_count', 'desc')->paginate()->appends([
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d')
        ]);

        return view('managers', [
            'stats' => self::getStats($from, $to),
            'managers' => $managers,
            'from' => $from,
            'to' => $to
        ]);
    }

    public function show(Request $request, Manager $manager)
    {
        list($from, $to) = self::getDates($request);
        list($dateStats, $count) = self::getDateStats($manager, $from, $to);

        $response = [
            'manager' => $manager,
            'dateStats' => $dateStats,
            'count' => $count,
            'from' => $from,
            'to' => $to
        ];

        $showValidator = Validator::make($request->all(), ['show' => 'required|date']);
        $show = $showValidator->fails() ? false : $request->input('show');

        if ($show) {
            $showFrom = Carbon::parse($show)->startOfDay();
            $showTo = Carbon::parse($show)->endOfDay();

            $response['show'] = $showFrom;
            $response['proposals'] = Proposal::with('location')->where('manager_id', '=', $manager->id)->where('created_at', '>=', $showFrom)->where('created_at', '<=', $showTo)->orderBy('created_at', 'desc')->paginate()->appends([
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
                'show' => $show
            ]);
        }

        return view('manager', $response);
    }
}
