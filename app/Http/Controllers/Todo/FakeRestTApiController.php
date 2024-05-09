<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class FakeRestTApiController extends Controller
{
    const FakerURL = "https://jsonplaceholder.typicode.com";

    private $httpClient;

    public function __construct(Http $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $response = Http::get(self::FakerURL . '/todos');

        return $response->successful()
            ? success_response($response->json(), 'Faker todos fetched successfully')
            : error_response([], 'Failed to fetch Todo items', $response->status());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $response = Http::get(self::FakerURL . "/todos/{$id}");

        return $response->successful()
            ? success_response($response->json(), 'Faker todo fetched successfully')
            : error_response([], 'Failed to fetch Todo item', $response->status());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => ['nullable', 'string'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $todo = $this->fetchTodoById($id);

        if (!$todo) {
            return error_response([], 'Todo item not found', 404);
        }

        $updatedFields = Arr::only($validatedData, array_keys($validatedData));

        if (empty($updatedFields)) {
            return error_response([], 'No fields provided for update', 400);
        }

        $response = Http::put(self::FakerURL . "/todos/{$id}", $updatedFields);

        return $response->successful()
            ? success_response($response->json(), 'Faker todo updated successfully')
            : error_response([], 'Failed to update Todo item', $response->status());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $response = Http::delete(self::FakerURL . "/todos/{$id}");

        return $response->successful()
            ? success_response([], 'Faker todo deleted successfully', 204)
            : error_response([], 'Failed to delete Todo item', $response->status());
    }

    /**
     * Fetches a todo item by its ID.
     */
    private function fetchTodoById(string $id): ?array
    {
        $response = Http::get(self::FakerURL . "/todos/{$id}");

        return $response->successful() ? $response->json() : null;
    }
}
