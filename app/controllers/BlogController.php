<?php
/**
 * =======================================================================================
 *                           GemFramework (c) GemPixel                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  GemPixel. If you find that this framework is packaged in a software not distributed 
 *  by GemPixel or authorized parties, you must not use this software and contact gempixel
 *  at https://gempixel.com/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package GemPixel\Premium-URL-Shortener
 * @author GemPixel (https://gempixel.com) 
 * @license https://gempixel.com/licenses
 * @link https://gempixel.com  
 */

use Core\Request;
use Core\DB;
use Core\Helper;
use Core\Localization;
use Core\View;
use Models\User;

class Blog {
    /**
     * Blog Posts
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public function index(){    

        if(!config('blog')) stop(404);

        $author = User::where('id', 1)->first();      
        $name = $author->name ?? ucfirst($author->username);
        $posts = [];
        
        foreach(DB::posts()->where('published', 1)->orderByDesc('date')->paginate(6) as $post){            
            if(strpos($post->content, '{{--more--}}') !== false){
                $post->content = Helper::readmore($post->content, '', null);
            } else {
                $post->content = Helper::readmore(Helper::truncate($post->content, 100), '', null);
            }
            $post->date = date('F d, Y', strtotime($post->date));
            $post->author = $name;
            $post->avatar = $author->avatar();
            $posts[] = $post;
        }

        View::set('title', e('Blog'));

        return View::with('blog', compact('posts'))->extend('layouts.main');
    }
    
    /**
     * Single Post
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @return void
     */
    public function post(string $slug){

        
        if(!$post = DB::posts()->where('slug', Helper::RequestClean($slug, 3))->where("published", 1)->first()){
            stop(404);
        }
        
        $post->views++;
        $post->save();

        $post->content = str_replace(['<!--more-->', '&lt;!--more--&gt;', '{{--more--}}'], '', $post->content);
        $post->date = date('F d, Y', strtotime($post->date));
        $author = User::where('id', 1)->first();
        $post->author = $author->name ?? ucfirst($author->username);
        $post->avatar = $author ? $author->avatar() : null;
        $name = $post->author;

        View::set('title', $post->meta_title ? $post->meta_title : $post->title);
        View::set('description', $post->meta_description ? $post->meta_description : Helper::truncate($post->content, 180));

        $posts = DB::posts()->where('published', 1)->whereNotEqual('id', $post->id)->orderByDesc('date')->limit(3)->map(function($post) use ($name) {
            $post->content = Helper::readmore($post->content, '', null);
            $post->date = date('F d, Y', strtotime($post->date));
            $post->author = $name;
            return $post;
        }); 

        $json = [
            "@context" => "https://schema.org",
            "@type" => "BlogPosting",
            "headline" => $post->title,
            "description" => Helper::truncate(strip_tags($post->content), 255),
            "datePublished" => Helper::dtime($post->date, DATE_ATOM),
            "url" => route('blog.post', [$slug]),
            "author" => [
                "@type" => "Person",
                "name" => $post->author
            ]
        ];

        if($post->image){
            View::set("image", uploads($post->image, 'blog'));
            $json['image'] = uploads($post->image, 'blog');
        }

        View::push('<script type="application/ld+json">'.json_encode($json).'</script>', 'custom')->tofooter();

        return View::with('blog_single', compact('post', 'posts'))->extend('layouts.main');
    }

}