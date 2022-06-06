<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index() {
        $books = Book::query()->paginate();
        return BookCollection::make($books);
    }

    public function store(Request $request) {

        $name = $request->get('name');
        $price = (float) $request->get('price');

        $book = Book::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $price,
        ]);

        return response()->json(BookResource::make($book), 201);
    }

    public function show(int $id) {

        $book = Book::findOrFail($id);

        return response()->json(BookResource::make($book));
    }

    public function update(Request $request, int $id) {

        $name = $request->get('name');
        $price = (float) $request->get('price');

        $book = Book::findOrFail($id);

        $book->update([
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $price,
        ]);

        return response()->json(BookResource::make($book));
    }

    public function destroy(int $id) {

        $book = Book::findOrFail($id);

        $book->delete();

        return response()->json(null, 204);
    }
}
