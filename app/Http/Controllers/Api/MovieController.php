<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\MovieService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\FilterMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\Resources\movieResource;


class MovieController extends Controller
{
    use ApiResponseTrait;

    protected $movieServices;

    /**
     * constractur to inject Movie Service Class
     * @param MovieService $movieServices
     */
    public function __construct(MovieService $movieServices)
    {
        //injecting the Movie Service into controller 
        $this->movieServices = $movieServices;

        $this->middleware('auth:sanctum')->except(['index', 'filter', 'sorting']);
    }


    // ============================================index==============================================================================
    /**
     * Display a listing of the movies.
     * @param  PaginationRequest $request
     * @return /illuminat\Http\JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        try {

            //per_page is the key that you should put it in the parameter of the request on postman
            $perPage = $request->input('per_page', 10);

            $movies = $this->movieServices->getAllMovie($perPage);

            $movies = movieResource::collection($movies);

            if ($movies->isNotEmpty()) {
                return $this->successResponse('This is all movies', $movies, 200);
            } else {
                return $this->notFound('There are not any movies!');
            }
        } catch (\Exception $e) {
            Log::error('Error in MovieController@index: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }




    //===========================================store============================================================================================================================================


    /**
     * Store a newly created movie in storage.
     * @param StoreMovieRequest $request
     * @return /illuminat\Http\JsonResponse
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


    //===========================================update============================================================================================================================================


    /**
     * Update the specified movie in storage.
     * @param  UpdateMovieRequest $request
     * @param  Movie $movie
     * @return /illuminat\Http\JsonResponse
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {

        try {

            $validationdata = $request->validated();
            $movie = $this->movieServices->updateMovie($movie, $validationdata);
            return $this->successResponse('successefuly added the movie', $movie, 201);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@update' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }


    //===========================================destroy============================================================================================================================================


    /**
     * Remove the specified movie from storage.
     * @param  Movie $movie
     * @return /illuminat\Http\JsonResponse
     */
    public function destroy(Movie $movie)
    {
        try {

            $movie = $this->movieServices->deleteMovie($movie);
            return $this->successResponse('successefully deleted', 200);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@destroy' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }



    //===========================================filter============================================================================================================================================


    /**
     * filter the movie by director or gener
     * @param FilterMovieRequest $request
     * @return /illuminat\Http\JsonResponse
     */
    public function filter(FilterMovieRequest $request)
    {
        try {
            //generOrDirector is the key that you should put it in the parameter of the request on postman
            $generOrDirector = $request->input('generOrDirector');
            $movies = $this->movieServices->filterMovie($generOrDirector);
            $movies = movieResource::collection($movies);

            if ($movies->isNotEmpty()) {
                return $this->successResponse('Filtering result', $movies, 200);
            } else
                return $this->errorResponse('there are not any movie!', 404);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@filter' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }


    //===========================================sorting============================================================================================================================================


    /**
     * sort the movie order by release year asc
     * @return /illuminat\Http\JsonResponse
     */
    public function sorting()
    {
        try {
            $movies = $this->movieServices->OrderedByReleaseYear();


            $movies = movieResource::collection($movies);

            if ($movies->isNotEmpty()) {

                return $this->successResponse('Movies ordered by release year', $movies, 200);
            } else {
                return $this->notFound('There are not any movies!');
            }
        } catch (\Exception $e) {
            Log::error('Error in MovieController@sorting: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
