<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $websites = Website::with('tags')->whereIn('group_id', auth()->user()->groups()->pluck('id'))->get();

        return view('websites.index', [
            'websites' => $websites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('websites.create', [
            'groups' => auth()->user()->groups()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateWebsite($request);

        Website::create($validated);

        return redirect()->route('websites.index');
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
        $website = $this->findAuthorizedWebsite($id);

        return view('websites.edit', [
            'website' => $website,
            'groups' => auth()->user()->groups()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $website = $this->findAuthorizedWebsite($id);
        $validated = $this->validateWebsite($request);

        $website->update($validated);

        return redirect()->route('websites.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $website = $this->findAuthorizedWebsite($id);
        $website->delete();

        return redirect()->route('websites.index');
    }

    private function validateWebsite(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'description' => 'nullable|string',
            'rating' => 'required|in:bad,average,good',
            'group_id' => 'required|exists:groups,id',
        ]);

        if (! auth()->user()->isOwnerOfGroup($validated['group_id'])) {
            abort(403, 'Unauthorized action.');
        }

        return $validated;
    }

    private function findAuthorizedWebsite(string $id): Website
    {
        $website = Website::findOrFail($id);

        if (! auth()->user()->isOwnerOfGroup($website->group_id)) {
            abort(403, 'Unauthorized action.');
        }

        return $website;
    }
}
