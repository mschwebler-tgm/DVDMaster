<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Service\Dao\MovieDao;
use App\Service\Facades\ContentTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MovieController extends Controller
{
    private $movieDao;

    public function __construct(MovieDao $movieDao)
    {
        $this->movieDao = $movieDao;
    }

    public function index()
    {
        $movies = Movie::with('actors', 'genres', 'pendingRental.user')
            ->orderBy('title', 'asc')->paginate();
        return ContentTransformer::transformContentPaginaton($movies, 'movies');
    }

    public function store(Request $request)
    {
        $movie = $request->get('movie');
        if (!$movie) {
            abort(500);
        }

        $isCustom = $request->get('is_custom') === 'true';
        if ($isCustom) {
            $posterPath = null;
            $posterPath = $this->savePoster($request);
            $backdropPath = null;
            $backdropPath = $this->saveBackdropImage($request);
            $this->movieDao->insertFromCustomArray($request->all(), $posterPath, $backdropPath);
        } else if ($isCustom === false) {
            $reqMovie = json_decode($movie, true);
            $movie = new Movie();
            $movie->tmdb_id = $reqMovie['id'];
            $movie->title = isset($reqMovie['title']) ? $reqMovie['title'] : '';
            $movie->comment = isset($reqMovie['comment']) ? $reqMovie['comment'] : null;
            $movie->blue_ray = isset($reqMovie['blue_ray']) ? $reqMovie['blue_ray'] : false;
            $movie->based_on_book = isset($reqMovie['based_on_book']) ? $reqMovie['based_on_book'] : false;
            $movie->true_story = isset($reqMovie['true_story']) ? $reqMovie['true_story'] : false;
            $movie->save();
            Artisan::call('import:movie', ['tmdbId' => $reqMovie['id']]);
        }
    }

    public function show($id)
    {
        return $this->movieDao->find($id);
    }

    public function destroy($id)
    {
        $movie = $this->movieDao->find($id);
        $movie->delete();
        return response('success', 200);
    }

    public function updateLastSeen($id, $date)
    {
        $date = new Carbon($date);
        $movie = $this->movieDao->find($id);
        $movie->update(['last_seen' => $date]);
        return response($movie->last_seen, 200);
    }

    public function borrowTo($movieId, $userId)
    {
        $movie = $this->movieDao->find($movieId);
        $user = User::find($userId);
        if (!$user) {
            abort(404);
        }

        return $this->movieDao->rent($movie, $user);
    }

    public function retrieveMovie($movieId, Request $request)
    {
        $movie = $this->movieDao->find($movieId);
        return $this->movieDao->retrieve($movie, $request->get('like'), $request->get('date'), $request->get('dvd_quality'), $request->get('case_quality'));
    }

    public function rateMovie($movieId, Request $request)
    {
        $movie = $this->movieDao->find($movieId);
        if (!$request->get('rating')) {
            abort(404);
        }

        $movie->custom_rating = $request->get('rating');
        $movie->save();
        return $movie;
    }

    public function updateMovie($movieId, Request $request)
    {
        $movie = $this->movieDao->find($movieId);
        if (!$request->get('movie')) {
            abort(404);
        }

        $payload = json_decode($request->get('movie'), true);
        $actors = $payload['actors'];
        $genres = $payload['genres'];
        $posterPath = $this->savePoster($request);
        if (!$posterPath) {
            $posterPath = $payload['poster_path'];
        }
        $backdropPath = $this->saveBackdropImage($request);
        if (!$backdropPath) {
            $backdropPath = $payload['backdrop_path'];
        }
        $payload['poster_path'] = $posterPath;
        $payload['backdrop_path'] = $backdropPath;

        $attributeKeys = array_keys($movie->getAttributes());
        $toUpdate = [];
        foreach ($attributeKeys as $key) {
            $toUpdate[$key] = $payload[$key];
        }
        $movie->update($toUpdate);
        $this->movieDao->updateGenres($movie, $genres);
        $this->movieDao->updateActors($movie, $actors);

        return $movie;
    }

    private function savePoster(Request $request)
    {
        if ($request->file('custom_poster')) {
            $posterPath = $request->file('custom_poster')->store('posters', 'public');
            return $posterPath;
        }
        return null;
    }

    private function saveBackdropImage(Request $request)
    {
        if ($request->file('custom_backdrop')) {
            $backdropPath = $request->file('custom_backdrop')->store('backdrops', 'public');
            return $backdropPath;
        }
        return null;
    }
}
