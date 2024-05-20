<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Packages::orderByDesc('created_at')
        ->paginate(10);

        return view('packages.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $this->validate($request, [
        //     'name' => 'required',
        //     'price' => 'required',
        //     'duration_months' => 'required',
        //     'max_contact_records' => 'required',
        // ]);

        $data = [
            'status' => isset($request->status) ? $request->status : 1,
            'name' => $request->name,
            'detail' => $request->detail,
            'price' => $request->price,
            'duration_months' => $request->duration_months,
            'max_contact_records' => $request->max_contact_records,
        ];

        Packages::create($data);

        return redirect()->route('packages.index')->with('success','Record created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Packages $packages)
    {
        if (!empty($packages)) {

            $data = [
                'package' => $packages
            ];
            return view('packages.show', $data);

        } else {
            return redirect()->route('packages.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Packages $packages)
    {
        dd($packages);
        return view('packages.edit', compact('packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Packages $packages)
    {

        $this->validate($request, [
            'amount' => 'required',
            'user_id' => 'required',
            'date' => 'required',
        ]);

        $data = [
            'status' => isset($request->status) ? $request->status : $packages->status,
            'dated' => isset($request->dated) ? $request->dated : $packages->dated,
            'amount' => $request->amount,
            'user_id' => $request->user_id,
            'detail' => $request->detail
        ];

        $updated = Packages::find($packages->id)->update($data);

        return redirect()->route('donations.index')->with('success','Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Packages $packages)
    {
        Packages::find($packages->id)->delete();
        return redirect()->route('packages.index')->with('success', 'Deleted successfully');
    }
}
