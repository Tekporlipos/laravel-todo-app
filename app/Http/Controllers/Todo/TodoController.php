<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $todos = Todo::where('user_id', $userId)->get();
            return success_response($todos, 'Todos fetched successfully');
        } catch (\Exception $e) {
            return error_response([], 'Failed to fetch Todos', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'completed' => ['nullable', 'boolean'],
        ]);

        try {
            $todo = Todo::create([
                'title' => $request->input('title'),
                'completed' => $request->input('completed', false),
                'user_id' => Auth::id(),
            ]);

            return success_response($todo, 'Todo created successfully');
        } catch (\Exception $e) {
            return error_response([], 'Failed to crate Todo', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $todo = Todo::findOrFail($id);
            return success_response($todo, 'Todo fetched successfully');
        } catch (\Exception $e) {
            return error_response([], 'Todo not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'title' => ['nullable', 'string'],
                'completed' => ['nullable', 'boolean'],
            ]);

            $todo = Todo::findOrFail($id);
            $updatedFields = $request->only('title', 'completed');

            if (empty($updatedFields)) {
                return error_response([], 'No fields provided for update', 400);
            }

            $todo->fill($updatedFields)->save();

            return success_response($todo, 'Todo updated successfully');
        } catch (\Exception $e) {
            return error_response([], 'Failed to update Todo', 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $todo = Todo::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $todo->delete();
            return success_response([], 'Todo deleted successfully', 204);
        } catch (\Exception $e) {
            return error_response([], 'Failed to delete Todo', 500);
        }
    }
}
