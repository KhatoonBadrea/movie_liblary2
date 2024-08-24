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
use App\Http\Requests\StoreRatingRequest;


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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        try {
            // عدد العناصر لكل صفحة
            $perPage = $request->input('per_page', 10);

            // الحصول على معايير الفرز
            $sortBy = $request->input('sort_by', 'release_year');
            $sortOrder = $request->input('sort_order', 'asc');

            // الحصول على معايير الفلترة
            $filterBy = $request->input('filter_by');
            $filterValue = $request->input('filter_value');

            // استدعاء الخدمة للحصول على الأفلام مع تطبيق الفلترة والفرز
            $movies = $this->movieServices->getMovies($perPage, $sortBy, $sortOrder, $filterBy, $filterValue);

            // تحويل النتائج إلى مورد (Resource)
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



    //===========================================filter============================================================================================================================================


    /**
     * filter the movie by director or gener
     * @param FilterMovieRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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






    public function rateMovie(StoreRatingRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();  // تأكد أن المستخدم مسجل دخول
            $rating = $this->movieServices->rateMovie($data);
            return $this->successResponse('Rating added successfully', $rating, 201);
        } catch (\Exception $e) {
            Log::error('Error in MovieController@rateMovie: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
