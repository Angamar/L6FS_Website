<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Tag;


class ArticlesController extends Controller
{
   
   public function index()
   {
       //Render a list of resource
    if(request('tag')) {
        $articles = Tag::where('name', request('tag'))->firstOrFail()->articles;
    } else {
        $articles = Article::latest()->get();
    }

    return view('articles.index', ['articles'=>$articles]);
   }
   
    public function show(Article $article)
    {
        //Show a single resource
        return view('articles.show', ['article'=>$article]);
    }

    public function create()
    {
        //Show a view to create a new resource
        return view('articles.create', ['tags' => Tag::all()]);
    }

    public function store()
    {
        $this->validateArticle();
        $article = new Article(request(['title', 'excerpt', 'body']));
        $article->user_id = 1;
        $article->save();

        $article->tags()->attach(request('tags'));
        
        //Article::create($this->validateArticle()); - fali user
 
        return redirect(route('articles.index'));
        //Persist the new resource
        
    }

    public function edit(Article $article)
    {
        //Show a view to edit an existing resource
        return view('articles.edit', compact('article'));
    }

    public function update(Article $article)
    {
        $article->update($this->validateArticle());


        return redirect($article->path());
        //Persist the editet resource

    }

    public function destroy()
    {
        //Delete the resource
        
    }

    protected function validateArticle()
    {
        return request()->validate([
            'title' => 'required',
            'excerpt' => 'required',
            'body' => 'required',
            'tags' => 'exists:tags,id'
        ]);
    }
}
    