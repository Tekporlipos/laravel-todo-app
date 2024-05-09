<?php

namespace Tests\Feature\Todo;

use App\Http\Controllers\Todo\TodoController;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index_fetches_all_todos_successfully()
    {
        $todos = Todo::factory()->count(3)->create();

        $response = $this->getTodoController()->index();

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson(['message' => 'Todos fetched successfully']);
    }

    public function test_show_with_valid_todo_id_fetches_todo_successfully()
    {
        $todo = Todo::factory()->create();

        $response = $this->getTodoController()->show($todo->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Todo fetched successfully']);
    }

    public function test_show_with_invalid_todo_id_returns_error()
    {
        $response = $this->getTodoController()->show(999);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Todo not found']);
    }

    public function test_update_with_valid_data_updates_todo_successfully()
    {
        $todo = Todo::factory()->create();
        $requestData = ['title' => 'Updated Title', 'completed' => true];
        $request = new Request([], $requestData);

        $response = $this->getTodoController()->update($request, $todo->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Todo updated successfully']);

        $this->assertDatabaseHas('todos', $requestData);
    }

    public function test_update_with_empty_data_returns_error()
    {
        $todo = Todo::factory()->create();
        $request = new Request([], []);

        $response = $this->getTodoController()->update($request, $todo->id);

        $response->assertStatus(400)
            ->assertJson(['message' => 'No fields provided for update']);
    }

    public function test_destroy_with_valid_todo_id_deletes_todo_successfully()
    {
        $todo = Todo::factory()->create();

        $response = $this->getTodoController()->destroy($todo->id);

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertDeleted($todo);
    }

    public function test_destroy_with_invalid_todo_id_returns_error()
    {
        $response = $this->getTodoController()->destroy(999);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Failed to delete Todo']);
    }

    private function getTodoController(): TodoController
    {
        return new TodoController();
    }
}
