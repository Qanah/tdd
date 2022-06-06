<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_books_can_be_listed_as_paginated_collection() {

        $books = Book::factory()->count(3)->create();

        $response = $this->get("/api/books");

        //info($response->getContent());

        $response->assertJsonStructure([
            'data' => [ '*' => ['id', 'name', 'slug', 'price', 'created_at'] ],
            "links" => [ "first", "last", "prev", "next" ],
            "meta" => [ "current_page", "from", "last_page", "path", "per_page", "to", "total" ]
        ])->assertJson([
            "data" => $books->only(['id', 'name', 'slug', 'price', 'created_at'])->all(),
            "meta" => [
                'total' => 3,
                'current_page' => 1,
                'per_page' => 15,
                'from' => 1,
                'to' => 3,
                'last_page' => 1
            ]
        ])->assertStatus(200);

    }

    public function test_books_can_be_created()
    {

        $response = $this->post('/api/books', [
            'name' => $name = $this->faker->company,
            'price' => $price = $this->faker->randomFloat(2,1,10),
        ]);

        //info($response->getContent());

        $this->assertDatabaseHas('books', [
            'name' => $name,
            'slug' => $slug = Str::slug($name),
            'price' => $price,
        ]);

        $response->assertJsonStructure([
            'id', 'name', 'slug', 'price', 'created_at'
        ])->assertJson([
            'name' => $name,
            'slug' => $slug,
            'price' => $price,
        ])->assertStatus(201);
    }

    public function test_books_cant_be_returned_if_its_not_found()
    {
        $response = $this->get("/api/books/-1");

        $response->assertStatus(404);
    }

    public function test_books_can_be_returned()
    {
        $book = Book::factory()->create();

        $response = $this->get("/api/books/{$book->id}");

        $response->assertJsonStructure([
            'id', 'name', 'slug', 'price', 'created_at'
        ])->assertExactJson([
            'id' => $book->id,
            'name' => $book->name,
            'slug' => $book->slug,
            'price' => $book->price,
            'created_at' => $book->created_at->toDateTimeString(),
        ])->assertStatus(200);
    }

    public function test_books_cant_be_updated_if_its_not_found()
    {
        $response = $this->put("/api/books/-1");

        $response->assertStatus(404);
    }

    public function test_books_can_be_updated()
    {
        $book = Book::factory()->create();

        $response = $this->put("/api/books/{$book->id}", [
            'name' => $name = $this->faker->company,
            'price' => $price = $this->faker->randomFloat(2,1,10),
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'name' => $name,
            'slug' => $slug = Str::slug($name),
            'price' => $price,
        ]);

        $response->assertJsonStructure([
            'id', 'name', 'slug', 'price', 'created_at'
        ])->assertExactJson([
            'id' => $book->id,
            'name' => $name,
            'slug' => $slug,
            'price' => $price,
            'created_at' => $book->created_at->toDateTimeString(),
        ])->assertStatus(200);
    }

    public function test_books_cant_be_deleted_if_its_not_found()
    {
        $response = $this->delete("/api/books/-1");

        $response->assertStatus(404);
    }

    public function test_books_can_be_deleted()
    {
        $book = Book::factory()->create();

        $response = $this->delete("/api/books/{$book->id}");

        $this->assertDatabaseMissing('books', [
            'id' => $book->id
        ]);

        $response->assertNoContent();
        //$response->assertSee(null)->assertStatus(204);
    }
}
