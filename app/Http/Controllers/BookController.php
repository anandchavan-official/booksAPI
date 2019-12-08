<?php

namespace App\Http\Controllers;

use App\Book;

use App\Traits\APIResponser;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{

    use APIResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

     /**
     * view a list of Books.
     *
     * @return Illuminate\Http\Response
     */
    public function index(){

        $books = Book::all();

        return $this->successResponse($books);
    }

    /**
     * create a new Books.
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request){

        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|min:1',
            'author_id' => 'required|min:1',
        ];

        $this->validate($request, $rules);

        $book = Book::create($request->all());
        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * View an existing Books.
     *
     * @return Illuminate\Http\Response
     */
    public function show($book){

        $book = Book::findOrFail($book);
        return $this->successResponse($book);
    }

    /**
     * update an existing Books.
     *
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $book){

        $rules = [
            'title' => 'max:255',
            'description' => 'max:255',
            'price' => 'min:1',
            'author_id' => 'min:1',
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($book);
        $book->fill($request->all());

        if ($book->isClean()){
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();
        return $this->successResponse($book);
    }

    /**
     * Remove an existing Books.
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($book){

        $book = Book::findOrFail($book);
        $book->delete();
        return $this->successResponse($book);
    }
}
