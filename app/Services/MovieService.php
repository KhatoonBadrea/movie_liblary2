<?php

namespace App\Services;

use App\Models\Movie;

class MovieService
{
    public function getAllMovie(int $perPage)
    {
        return Movie::paginate($perPage);
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
    }


    public function updateMovie(Movie $movie, $data)
    {

        return $movie->update($data);
    }


    public function deleteMovie(Movie $movie)
    {
        return $movie->delete();
    }

    public function filterMovie($data)
    {
        return Movie::where('gener', $data)->orwhere('director', $data)->get();
    }

    // public function getMoviesOrderedByReleaseYear()
    // {
    //     return Movie::orderBy('release_year', 'asc');
    // }
}
