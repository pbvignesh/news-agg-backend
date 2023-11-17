<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function __invoke(Request $request)
    {
        return Feed::getUserNewsFeed(Auth::id(), $request->query());
    }
}
