<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mails = Mail::all();
        return view('admin.mails', compact('mails'));
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
        $data['mail_id'] = 'COURRIERJSS' . date('Y') ." ". str_pad(!empty(Mail::orderByDesc('id')->first()) ? Mail::orderByDesc('id')->first()->id : 0, 4, '0', STR_PAD_LEFT);
        
        Mail::create($data);
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
        $mail = Mail::find($id);
        $data = $request->except('_token');
        
        $mail->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mail = Mail::find($id);
        $mail->delete();
        return back();
    }
}
