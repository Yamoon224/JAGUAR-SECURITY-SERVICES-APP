<?php

namespace App\Http\Controllers;

use App\Models\Meet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MeetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meets = Meet::where('deleted', 0)->get();
        return view('admin.meets', compact('meets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['file_path'] = $request->file('file_path')->store('public/meets');
        $data['file_path'] = substr($data['file_path'], 7);
        Meet::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $meet = Meet::find($id);
        $data = $request->except('_token');
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('public/meets');
            $data['file_path'] = substr($data['file_path'], 7);
            File::delete("storage/".$meet->file_path);
        }
        $meet->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meet = Meet::find($id);
        $meet->update(['deleted'=>1]);
        return back();
    }
}
