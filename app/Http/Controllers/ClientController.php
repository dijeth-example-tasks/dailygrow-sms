<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        $response = $request->json();
        $client = Client::create([
            'phone' => $response->get('phone'),
            'birthday' => $response->get('birthday'),
            'name' => $response->get('name'),
        ]);

        $client->segments()->attach($response->get('segment_id'));
        $client->save();

        return response()->json(getApiResponse($client->toArray()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $response = $request->json();

        $client->phone = $response->get('phone') ?? $client->phone;
        $client->birthday = $response->get('birthday') ?? $client->birthday;
        $client->name = $response->get('name') ?? $client->name;

        $client->save();

        return response()->json(getApiResponse($client->toArray()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(getApiResponse($client->toArray()));
    }
}
