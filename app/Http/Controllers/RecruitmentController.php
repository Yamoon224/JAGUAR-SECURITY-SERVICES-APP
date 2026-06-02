<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recruitments = Recruitment::all();
        return view('admin.recruitments', compact('recruitments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $recruitment = Recruitment::orderByDesc('id')->first();
        return view('recruitment', compact('recruitment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        if ($request->hasFile('path')) 
            $data['path'] = $request->file('path')->store('public/recruitments');
        
        $data['title'] = "JSSRECRUTEMENT " . date('dm', strtotime($data['start_date'])). "-" .date('dmY', strtotime($data['end_date']));
        Recruitment::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Applicant $applicant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $recruitment = Recruitment::find($id);
        $data = $request->except('_token');

        if ($request->hasFile('path')) {
            $data['path'] = $request->file('path')->store('public/recruitments');
            File::delete("storage/".$recruitment->path);
        }
        $data['title'] = "JAGUARECRUTEMENT " . date('dmY', strtotime($data['start_date'])). " - " .date('dmY', strtotime($data['end_date']));
        $recruitment->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        
    }
}
