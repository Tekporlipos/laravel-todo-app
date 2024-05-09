<?php

namespace Tests\Feature\Todo;

use App\Http\Controllers\Todo\FakeRestTApiController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FakeRestTApiControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index_fetches_todo_items_successfully()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos' => Http::response(['data' => 'fake_todo_data'], 200),
        ]);

        $response = $this->getFakeRestApiController()->index();

        $response->assertStatus(200)
            ->assertJson(['message' => 'Faker todos fetched successfully']);
    }

    public function test_show_with_valid_todo_id_fetches_todo_item_successfully()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos/1' => Http::response(['data' => 'fake_todo_data'], 200),
        ]);

        $response = $this->getFakeRestApiController()->show(1);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Faker todo fetched successfully']);
    }

    public function test_show_with_invalid_todo_id_returns_error()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos/100' => Http::response([], 404),
        ]);

        $response = $this->getFakeRestApiController()->show(100);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Failed to fetch Todo item']);
    }

    public function test_update_with_valid_data_updates_todo_successfully()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos/1' => Http::response(['data' => 'fake_todo_data'], 200),
        ]);

        $request = new Request([], ['title' => 'New Title', 'completed' => true]);

        $response = $this->getFakeRestApiController()->update($request, 1);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Faker todo updated successfully']);
    }

    public function test_update_with_invalid_todo_id_returns_error()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos/100' => Http::response([], 404),
        ]);

        $request = new Request([], ['title' => 'New Title', 'completed' => true]);

        $response = $this->getFakeRestApiController()->update($request, 100);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Todo item not found']);
    }

    public function test_update_with_empty_data_returns_error()
    {
        $request = new Request([], []);

        $response = $this->getFakeRestApiController()->update($request, 1);

        $response->assertStatus(400)
            ->assertJson(['message' => 'No fields provided for update']);
    }

    public function test_destroy_with_valid_todo_id_deletes_todo_successfully()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos/1' => Http::response([], 204),
        ]);

        $response = $this->getFakeRestApiController()->destroy(1);

        $response->assertStatus(204)
            ->assertNoContent();
    }

    public function test_destroy_with_invalid_todo_id_returns_error()
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos/100' => Http::response([], 404),
        ]);

        $response = $this->getFakeRestApiController()->destroy(100);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Failed to delete Todo item']);
    }

    private function getFakeRestApiController(): FakeRestTApiController
    {
        return new FakeRestTApiController(Http::class);
    }
}
