<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Log;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Return all blogs sorted from latest
        $blogsList = Blog::orderBy('created_at','desc')->get()->toArray();
        return json_encode($blogsList);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Parse input from request
        $title = $request->input('title');
        $content = $request->input('content');

        //Create new Nog Entry
        $newBlog = Blog::create([
            'blog_title' =>  $title,
            'blog_text' =>  nl2br($content),
            'author_id' => Auth::user()->id
        ]);

        return $newBlog->id ? 'success' : 'failure';
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //Get Blog based on passed ID
        $blogDetails = Blog::find($id)->toArray();
        $blogDetails['author_id'] = User::find($blogDetails['author_id'])->name;
        $comments = Comment::where('article_id', $id)->get()->toArray();

        //Iterate all comments and replace users IDs by names
        foreach($comments as &$comment) {
            $userInfo = User::find($comment['comment_by']);
            $comment['comment_by'] = $userInfo->name;
        }
        $blogDetails['comments'] = $comments;
        return json_encode($blogDetails);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blogs $blogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blogs $blogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blogs $blogs)
    {
        //
    }
}
