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
        $mails = Mail::orderByDesc('mail_datetime')->orderByDesc('id')->get();
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
        $data = $this->validated($request);
        $data['mail_id'] = 'COURRIERJSS ' . date('Y') . ' ' . str_pad((int) Mail::max('id') + 1, 4, '0', STR_PAD_LEFT);

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
        Mail::findOrFail($id)->update($this->validated($request));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Mail::findOrFail($id)->delete();
        return back();
    }

    /**
     * Champs autorisés pour un courrier.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'mail_datetime' => ['required', 'date'],
            'name' => ['required', 'in:DEPART,ARRIVEE'],
            'srce' => ['required', 'string', 'max:255'],
            'destinator' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'observation' => ['nullable', 'string'],
        ]);
    }
}
