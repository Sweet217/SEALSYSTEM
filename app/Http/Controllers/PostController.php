<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        return Post::paginate(10);
    }

    public function createPost(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'user_id' => ['required', 'exists:usuarios,user_id']
        ]);

        return Post::create($data);
    }
}