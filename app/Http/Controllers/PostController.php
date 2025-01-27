<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class PostController extends Controller
{
    function add_post(){
        $categories = Category::all();
        $tags = Tag::all();
        return view('frontend.author.add_post', [
            'categories'=>$categories,
            'tags'=>$tags,
        ]);
    }
    function post_store(Request $request){
        //preview
        $preview = $request->preview;
        $extension = $preview->extension();
        $preview_name = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($preview);
        $image->resize(1000, 600);
        $image->save(public_path('uploads/post/preview/'.$preview_name));

        //thumbnail
        $thumbnail = $request->thumbnail;
        $extension = $thumbnail->extension();
        $thumbnail_name = uniqid().'.'.$extension;
    
        $manager = new ImageManager(new Driver());
        $image = $manager->read($thumbnail);
        $image->resize(300, 200);
        $image->save(public_path('uploads/post/thumbnail/'.$thumbnail_name));

        Post::insert([
            'author_id'=>Auth::guard('author')->id(),
            'category_id'=>$request->category_id,
            'read_time'=>$request->read_time,
            'title'=>$request->title,
            'slug'=>Str::lower(str_replace(' ', '-', $request->title)).'-'.random_int(100000, 200000),
            'desp'=>$request->desp,
            'tags'=>implode(',', $request->tag_id),
            'preview'=>$preview_name,
            'thumbnail'=>$thumbnail_name,
            'created_at'=>Carbon::now(),
        ]);
        return back();
    }

    function my_post(){
        $posts = Post::where('author_id', Auth::guard('author')->id())->paginate(2);
        return view('frontend.author.my_post', [
            'posts'=>$posts,
        ]);
    }
    function my_post_status($post_id){
        $post = Post::find($post_id);
        if($post->status == 1){
            Post::find($post_id)->update([
                'status'=>0,
            ]);
            return back();
        }
        else{
            Post::find($post_id)->update([
                'status'=>1,
            ]);
            return back();
        }
    }
    function my_post_delete($post_id){
        $post = Post::find($post_id);
        //preview
        $delete_from = public_path('uploads/post/preview/'.$post->preview);
        unlink($delete_from);
        //thumbnail
        $delete_from2 = public_path('uploads/post/thumbnail/'.$post->thumbnail);
        unlink($delete_from2);

        Post::find($post_id)->delete();
        return back();


    }
}
