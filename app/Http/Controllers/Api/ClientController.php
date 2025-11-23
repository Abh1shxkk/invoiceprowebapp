<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients
     * 
     * GET /api/clients
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Client::where('user_id', $request->user()->id);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json($clients);
    }

    /**
     * Store a newly created client
     * 
     * POST /api/clients
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $client = $request->user()->clients()->create($validated);

        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client,
        ], 201);
    }

    /**
     * Display the specified client
     * 
     * GET /api/clients/{id}
     * 
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Client $client)
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client->load('invoices');

        return response()->json($client);
    }

    /**
     * Update the specified client
     * 
     * PUT /api/clients/{id}
     * 
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Client $client)
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $client->update($validated);

        return response()->json([
            'message' => 'Client updated successfully',
            'client' => $client,
        ]);
    }

    /**
     * Remove the specified client
     * 
     * DELETE /api/clients/{id}
     * 
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Client $client)
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully',
        ]);
    }
}
