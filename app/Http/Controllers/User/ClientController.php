<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with search functionality.
     */
    public function index(Request $request)
    {
        $query = Client::where('user_id', auth()->id())
            ->with('invoices');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15);

        return view('user.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('user.clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['user_id'] = auth()->id();

        Client::create($validated);

        return redirect()->route('user.clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified client with invoices and statistics.
     */
    public function show(Client $client)
    {
        // Ensure user can only view their own clients
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $client->load('invoices');

        // Calculate statistics
        $totalBusiness = $client->invoices()
            ->where('status', 'paid')
            ->sum('total');

        $outstandingAmount = $client->invoices()
            ->whereIn('status', ['draft', 'sent'])
            ->sum('total');

        $totalInvoices = $client->invoices()->count();

        return view('user.clients.show', compact(
            'client',
            'totalBusiness',
            'outstandingAmount',
            'totalInvoices'
        ));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        // Ensure user can only edit their own clients
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Ensure user can only update their own clients
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:50'],
        ]);

        $client->update($validated);

        return redirect()->route('user.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        // Ensure user can only delete their own clients
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if client has invoices
        if ($client->invoices()->count() > 0) {
            return redirect()->route('user.clients.index')
                ->with('error', 'Cannot delete client with existing invoices.');
        }

        $client->delete();

        return redirect()->route('user.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
