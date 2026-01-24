<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Offer;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\OfferComplaintsRepositoryInterface;
use App\Http\Requests\Dashboard\OfferComplaints\StoreOfferComplaintRequest;
use App\Http\Requests\Dashboard\OfferComplaints\UpdateOfferComplaintRequest;

class OfferComplaintsController extends Controller
{
    public function __construct(private OfferComplaintsRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'user_id' => request('user_id'),
            'offer_id' => request('offer_id'),
        ], (int) request('per_page', 10));

        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();

        return view('dashboard.offer_complaints.index', compact('rows', 'users', 'offers'));
    }

    public function create()
    {
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.offer_complaints.create', compact('users', 'offers'));
    }

    public function store(StoreOfferComplaintRequest $request)
    {
        $this->repo->create($request->validated());
        return redirect()->route('dashboard.offer-complaints.index')->with('success', 'Complaint created');
    }

    public function edit($offer_complaint)
    {
        $row = $this->repo->findOrFail((int)$offer_complaint);
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.offer_complaints.edit', compact('row', 'users', 'offers'));
    }

    public function update(UpdateOfferComplaintRequest $request, $offer_complaint)
    {
        $this->repo->update((int)$offer_complaint, $request->validated());
        return redirect()->route('dashboard.offer-complaints.index')->with('success', 'Complaint updated');
    }

    public function destroy($offer_complaint)
    {
        $this->repo->delete((int)$offer_complaint);
        return back()->with('success', 'Complaint deleted');
    }
}
