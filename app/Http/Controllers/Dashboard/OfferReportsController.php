<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Offer;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\OfferReportsRepositoryInterface;
use App\Http\Requests\Dashboard\OfferReports\StoreOfferReportRequest;
use App\Http\Requests\Dashboard\OfferReports\UpdateOfferReportRequest;

class OfferReportsController extends Controller
{
    public function __construct(private OfferReportsRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'user_id' => request('user_id'),
            'offer_id' => request('offer_id'),
        ], (int) request('per_page', 10));

        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();

        return view('dashboard.offer_reports.index', compact('rows', 'users', 'offers'));
    }

    public function create()
    {
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.offer_reports.create', compact('users', 'offers'));
    }

    public function store(StoreOfferReportRequest $request)
    {
        $this->repo->create($request->validated());
        return redirect()->route('dashboard.offer-reports.index')->with('success', 'Report created');
    }

    public function edit($offer_report)
    {
        $row = $this->repo->findOrFail((int)$offer_report);
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.offer_reports.edit', compact('row', 'users', 'offers'));
    }

    public function update(UpdateOfferReportRequest $request, $offer_report)
    {
        $this->repo->update((int)$offer_report, $request->validated());
        return redirect()->route('dashboard.offer-reports.index')->with('success', 'Report updated');
    }

    public function destroy($offer_report)
    {
        $this->repo->delete((int)$offer_report);
        return back()->with('success', 'Report deleted');
    }
}
