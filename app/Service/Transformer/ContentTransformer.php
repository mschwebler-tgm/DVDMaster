<?php

namespace App\Service\Transformer;

use App\Movie;
use App\Series;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContentTransformer
{
    /**
     * @param $content LengthAwarePaginator
     * @param $type string
     * @return array
     */
    public function transformContentPaginaton($content, $type)
    {
        $queryParams = request()->all();
        if (request()->has('page')) {
            unset($queryParams['page']);
        }
        $queryParams = http_build_query($queryParams);
        $transformedContent = [
            'type' => $type,
            'currentPage' => $content->currentPage(),
            'lastPage' => $content->lastPage(),
            'perPage' => $content->perPage(),
            'prev_page_url' => $content->previousPageUrl() ? $content->previousPageUrl() . '&' . $queryParams : null,
            'next_page_url' => $content->nextPageUrl() ? $content->nextPageUrl() . '&' . $queryParams : null,
            'first_page_url' => $content->url(0),
            'last_page_url' => $content->url($content->lastPage()),
            'path' => request()->url(),
            'from' => ($content->currentPage() - 1) * $content->perPage() + 1,
            'to' => ($content->currentPage() - 1) * $content->perPage() + $content->perPage() > $content->total() ?
                    $content->total() :
                    ($content->currentPage() - 1) * $content->perPage() + $content->perPage(),
            'total' => $content->total(),
            'data' => $this->transformContentItems($content->items(), $type === 'movies')
        ];
        return $transformedContent;
    }

    /**
     * @param $items Movie|Series
     * @param $isMovie boolean
     * @return array
     */
    private function transformContentItems($items, $isMovie)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'tmdb_id' => $item->tmdb_id,
                'imdb_id' => $item->imdb_id,
                'url' => ($isMovie ? '/movie/' : '/series/'). $item->id,
                'api' => '/api' . ($isMovie ? '/movie/' : '/series/'). $item->id,
                'type' => $isMovie ? 'movie' : 'series',
                'title' => $isMovie ? $item->title : $item->name,
                'original_title' => $isMovie ? $item->original_title : $item->original_name,
                'overview' => $item->overview,
                'tagline' => $isMovie ? $item->tagline : null,
                'comment' => $item->comment,
                'duration' => $isMovie ? $item->duration : $item->episode_runtime,
                'custom_rating' => $item->custom_rating,
                'vote_average' => $item->vote_average,
                'vote_count' => $item->vote_count,
                'budget' => $isMovie ? $item->budget : null,
                'revenue' => $isMovie ? $item->revenue : null,
                'homepage' => $item->homepage,
                'popularity' => $item->popularity,
                'release_date' => $isMovie ? $item->release_date : $item->first_air_date,
                'based_on_book' => $item->based_on_book,
                'true_story' => $item->true_story,
                'blue_ray' => $item->blue_ray,
                'belongs_to_collection' => $isMovie ? $item->belongs_to_collection : null,
                'last_seen' => $item->last_seen,
                'sorted_after' => $item->sorted_after,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'backdrop_path' => $item->backdrop_path,
                'poster_path' => $item->poster_path,
                'pending_rental' => $item->pendingRental->toArray(),
                'genres' => $item->genres->toArray(),
                'actors' => $item->actors->toArray(),
            ];
        }
        return $data;
    }
}