<?php

namespace App\Service\Transformer;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Route;

class ContentTransformer
{
    /**
     * @param $content LengthAwarePaginator
     * @param $type string
     */
    public function transformContentPaginaton($content, $type)
    {
        $transformedContent = [
            'type' => $type,
            'currentPage' => $content->currentPage(),
            'lastPage' => $content->lastPage(),
            'perPage' => $content->perPage(),
            'first_page_url' => $content->url(0),
            'last_page_url' => $content->url($content->lastPage()),
            'path' => request()->url(),
            'from' => $content->currentPage() * $content->perPage() + 1,
            'to' => $content->currentPage() * $content->perPage() + $content->perPage(),
            'total' => $content->total(),
            'data' => $content->items()
        ];
        dd($transformedContent);
    }
}