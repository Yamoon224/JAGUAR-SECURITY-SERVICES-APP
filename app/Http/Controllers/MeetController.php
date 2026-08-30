<?php

namespace App\Http\Controllers;

use App\Models\Meet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meets = Meet::where('deleted', 0)->orderByDesc('id')->get();
        return view('admin.meets', compact('meets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['file_path'] = $this->storeFile($request);

        Meet::create($data);
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $meet = Meet::findOrFail($id);
        $data = $this->validated($request);

        if ($request->hasFile('file_path')) {
            if ($meet->file_path) {
                Storage::disk('public')->delete($meet->file_path);
            }
            $data['file_path'] = $this->storeFile($request);
        }

        $meet->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Meet::findOrFail($id)->update(['deleted' => 1]);
        return back();
    }

    /**
     * Champs autorisés pour une réunion.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'object' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string'],
            'file_path' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [], ['file_path' => 'compte rendu']);
    }

    /**
     * Enregistre le PDF sur le disque public et retourne son chemin relatif.
     */
    private function storeFile(Request $request): ?string
    {
        if (! $request->hasFile('file_path')) {
            return null;
        }

        return $request->file('file_path')->store('meets', 'public');
    }
}
