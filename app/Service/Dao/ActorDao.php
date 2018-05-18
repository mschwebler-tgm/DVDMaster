<?php

namespace App\Service\Dao;

use App\Actor;

class ActorDao
{
    public function insertFromArray($actorArray)
    {
        if (!isset($actorArray['id'])) {
            return null;
        }

        $actor = Actor::where('tmdb_id', $actorArray['id'])->first();
        if ($actor) {
            return $actor;
        }

        $actor = Actor::create([
            "birthday" => isset($actorArray['birthday']) ? $actorArray['birthday'] : null,
            "deathday" => isset($actorArray['deathday']) ? $actorArray['deathday'] : null,
            "tmdb_id" => isset($actorArray['id']) ? $actorArray['id'] : null,
            "imdb_id" => isset($actorArray['imdb_id']) ? $actorArray['imdb_id'] : null,
            "name" => isset($actorArray['name']) ? $actorArray['name'] : null,
            "also_known_as" => isset($actorArray['also_known_as']) && count($actorArray['also_known_as']) > 0 ? $actorArray['also_known_as'][0] : null,
            "gender" => isset($actorArray['gender']) ? $actorArray['gender'] : null,
            "biography" => isset($actorArray['biography']) ? $actorArray['biography'] : null,
            "popularity" => isset($actorArray['popularity']) ? $actorArray['popularity'] : null,
            "place_of_birth" => isset($actorArray['place_of_birth']) ? $actorArray['place_of_birth'] : null,
            "profile_path" => isset($actorArray['profile_path']) ? $actorArray['profile_path'] : null,
            "adult" => isset($actorArray['adult']) ? $actorArray['adult'] : null,
            "homepage" => isset($actorArray['homepage']) ? $actorArray['homepage'] : null,
            "image" => isset($actorArray['image']) ? $actorArray['image'] : null
        ]);
        return $actor;
    }
}