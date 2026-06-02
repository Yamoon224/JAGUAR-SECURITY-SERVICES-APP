<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreApplicantRequest;
use App\Http\Requests\UpdateApplicantRequest;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applicants = Applicant::where(['deleted'=>0, 'status'=>'inprogress'])->get();
        return view('admin.applicants', compact('applicants'));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function hires()
    {
        $applicants = Applicant::where(['deleted'=>0, 'status'=>'hired'])->get();
        return view('admin.hires', compact('applicants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.applicants.add');
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function notification($locale, $id)
    {
        app()->setLocale($locale ?? app()->getLocale());
        return view('notification', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicantRequest $request)
    {
        $item = Applicant::where('deleted', 0)->get()->last();
        $data = $request->except('_token');
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('public/photos');
        }
        if ($request->hasFile('path')) {
            $data['path'] = $request->file('path')->store('public/applications');
        }
        $data['applicationid'] = "N° DOSSIER " . str_pad((is_null($item->id) ? 0 : $item->id) + 1, 4, 0, STR_PAD_LEFT);
        Applicant::create($data);
        return auth()->check() ? back() : redirect()->route('applicants.done',  ['fr', $data['applicationid']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $applicant = Applicant::find($id);
        $applicant->status = 1;
        $applicant->save();
        return back();
    }
    
    /**
     * Display the specified resource.
     */
    public function delete(int $id)
    {
        $applicant = Applicant::find($id);
        $applicant->update(['deleted'=>1]);
        return back();
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
    public function update(UpdateApplicantRequest $request, int $id)
    {
        $applicant = Applicant::find($id);
        $data = $request->except('_token');
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('public/photos');
            File::delete("storage/".$applicant->photo);
        }

        if ($request->hasFile('path')) {
            $data['path'] = $request->file('path')->store('public/applications');
            File::delete("storage/".$applicant->path);
        }
        $applicant->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $applicant)
    {
        $applicant = Applicant::find($applicant);
        $applicant->update(['deleted'=>1]);
        return back();
    }
}
