<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\MovieService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\Resources\movieResource;


class MovieController extends Controller
{
    use ApiResponseTrait;

    protected $movieServices;

    /**
     * constractur
     * @param App\Services\
     */
    public function __construct(MovieService $movieServices)
    {
        $this->movieServices = $movieServices;
    }

    /**
     * Display a listing of the movies.
     * @return /illuminat/http/json
     */
    public function index()
    {
        try {

            $movies = $this->movieServices->getAllMovie();
            $movies = movieResource::collection($movies);
            if ($movies->isNotEmpty()) {
                return $this->successResponse('this is all movies', $movies, 200);
            } else {
                return $this->notFound('there are not any movies!');
            }
        } catch (\Exception $e) {
            Log::error('Error in MovieController@index' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Store a newly created movie in storage.
     * @param App\Http\Requests\
     * @return /illuminat/http/json
     */
    public function store(StoreMovieRequest $request)
    {
        try {

            $validationdata = $request->validated();
            $movie = $this->movieServices->createMovie($validationdata);
            return $this->successResponse('successefuly added the movie', $movie, 201);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@store' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {

        try {

            $validationdata = $request->validated();
            $movie = $this->movieServices->updateMovie($movie, $validationdata);
            return $this->successResponse('successefuly added the movie', $movie, 201);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@upate' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie = $this->movieServices->deleteMovie($movie);
        return $this->successResponse('successefully deleted', 200);
    }
}
