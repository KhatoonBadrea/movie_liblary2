<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use App\Models\Favourite;
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
use App\Http\Requests\StoreRatingRequest;


class MovieController extends Controller
{
    use ApiResponseTrait;

    protected $movieServices;
    //
    //
    /**
     * constractur to inject Movie Service Class
     * @param MovieService $movieServices
     */
    public function __construct(MovieService $movieServices)
    {
        //injecting the Movie Service into controller 
        $this->movieServices = $movieServices;

        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }


    // ============================================index==============================================================================
    /**
     * Display a listing of the movies.
     * @param  PaginationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        try {
            // per_page:that must be put in the paramete in the request in the postman
            $perPage = $request->input('per_page', 10);

            //sort by release_year
            $sortBy = $request->input('sort_by', 'release_year');

            //specify that the sort is asc or desc
            $sortOrder = $request->input('sort_order', 'asc');

            // filter_by dener or director
            $filterBy = $request->input('filter_by');

            //select the value of the field
            $filterValue = $request->input('filter_value');

            $movies = $this->movieServices->getMovies($perPage, $sortBy, $sortOrder, $filterBy, $filterValue);

            $movies = movieResource::collection($movies);

            if ($movies->isNotEmpty()) {
                return $this->successResponse('Movies list', $movies, 200);
            } else {
                return $this->notFound('No movies found!');
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
     * @return \Illuminate\Http\JsonResponse
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

    //===========================================show============================================================================================================================================


    /**
     * Display the specified movie with rating and review.
     * @param Movie $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Movie $movie)
    {
        try {
            // تحميل الفيلم مع التقييمات والمراجعات المرتبطة به
            $movie->load('ratings.user'); // تحميل التقييمات مع معلومات المستخدم

            $movieResource = new movieResource($movie);

            return $this->successResponse('Movie details with ratings and reviews', $movieResource, 200);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@show: ' . $e->getMessage());

            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }




    //===========================================update============================================================================================================================================


    /**
     * Update the specified movie in storage.
     * @param  UpdateMovieRequest $request
     * @param  Movie $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {

        try {

            $validationdata = $request->validated();
            $newMovie = $this->movieServices->updateMovie($movie, $validationdata);
            return $this->successResponse('successefuly edite the movie', $newMovie, 200);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@update' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }


    //===========================================destroy============================================================================================================================================


    /**
     * Remove the specified movie from storage.
     * @param  Movie $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Movie $movie)
    {
        try {

            $movie = $this->movieServices->deleteMovie($movie);
            return $this->successResponse('successefully deleted', 204);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@destroy' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }



    //===========================================rateMovie============================================================================================================================================

    /**
     * rate the movie by the auth user
     * @param StoreRatingRequest $request
     * @param Movie $movie
     * @return \Illuminate\Http\JsonResponse
     */

    public function rateMovie(StoreRatingRequest $request, Movie $movie)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $data['movie_id'] = $movie->id;

            $rating = $this->movieServices->rateMovie($data);

            return $this->successResponse('Rating added successfully', $rating, 201);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@rateMovie: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }


    //===========================================addToFavorites============================================================================================================================================


    /**
     * add movie to favourit list
     *@param Request $request
     *@param $movieId
     * @return \Illuminate\Http\JsonRespons
     */


    public function addToFavorites(Request $request, $movieId)
    {
        try {
            $userId = auth()->id();
            $result = $this->movieServices->addToFavorites($userId, $movieId);

            if (is_string($result)) {
                return response()->json(['message' => $result], 200);
            }

            return response()->json(['message' => 'Movie added to favorites successfully.'], 201);
        } catch (\Exception $e) {
            Log::error('Error in MovieService@addToFavorites: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while adding to favorites.'], 500);
        }
    }
}
