<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $movies = Movie::query()
            ->where('status', 'active')
            ->when($request->query('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->query('genre'), fn ($q, $g) => $q->where('genre', $g))
            ->latest()
            ->paginate(12);

        return MovieResource::collection($movies);
    }

    public function show(Movie $movie): MovieResource
    {
        return new MovieResource($movie);
    }

    public function store(Request $request): JsonResponse
    {
        $movie = Movie::create($this->validated($request));

        return (new MovieResource($movie))->response()->setStatusCode(201);
    }

    public function update(Request $request, Movie $movie): MovieResource
    {
        $movie->update($this->validated($request, true));

        return new MovieResource($movie);
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();

        return response()->json(null, 204);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'title' => [$required, 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => [$required, 'integer', 'min:1', 'max:600'],
            'genre' => ['nullable', 'string', 'max:100'],
            'rating' => ['nullable', 'string', 'max:20'],
            'poster_url' => ['nullable', 'string', 'max:2048'],
            'status' => ['sometimes', 'in:active,archived'],
        ]);
    }
}
