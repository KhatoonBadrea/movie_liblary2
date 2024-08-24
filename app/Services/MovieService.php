<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Rating;

class MovieService
{

    public function getMovies(int $perPage, $sortBy = 'release_year', $sortOrder = 'asc', $filterBy = null, $filterValue = null)
    {
        $query = Movie::query();

        // تطبيق الفلترة إذا تم تحديدها
        if ($filterBy && $filterValue) {
            $query->where($filterBy, $filterValue);
        }

        // تطبيق الفرز
        $query->orderBy($sortBy, $sortOrder);

        // إرجاع النتائج باستخدام Pagination
        return $query->paginate($perPage);
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
    }


    public function updateMovie(Movie $movie, $data)
    {

        $movie->update($data);
        return $movie;
    }


    public function deleteMovie(Movie $movie)
    {
        return $movie->delete();
    }

    public function filterMovie($data)
    {
        return Movie::where('gener', $data)->orwhere('director', $data)->get();
    }

    public function OrderedByReleaseYear()
    {
        return Movie::orderBy('release_year', 'asc')->get();
    }

    public function rateMovie(array $data)
    {
        return Rating::create($data);
    }
}
