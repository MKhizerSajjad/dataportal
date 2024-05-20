<?php

namespace App\Http\Controllers;

use App\Models\Vpn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VpnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vpn.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'id_no' => $request->id_no ?? '',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        Vpn::create($data);

        return redirect()->route('dashboard')->with('success','VPN request created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vpn $vpn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vpn $vpn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vpn $vpn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vpn $vpn)
    {
        //
    }
}
