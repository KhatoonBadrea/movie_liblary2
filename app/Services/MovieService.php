<?php

namespace App\Services;

use App\Models\Movie;

class MovieService
{
    public function getAllMovie()
    {
        return Movie::all();
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
    }

    
    public function updateMovie(Movie $movie, $data){

        return $movie->update($data);
    }


    public function deleteMovie(Movie $movie)
    {
        return $movie->delete();
    }
}
