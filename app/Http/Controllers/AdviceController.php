<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Advice;
use App\Models\Category;
use Storage;
use Session;

class AdviceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $advices = Advice::all();
        return view("advice.index", compact("advices"));
    }

    public function create()
    {
        $categories = Category::all();
        return view("advice.create", compact("categories"));
    }

    public function store(Request $request)
    {
        $cover_path = null;
        $currentURL = url()->current();
        $newURL = str_replace("advice", "", $currentURL);

        $request->validate([
            "title" => "required|unique:advice,title",
            "category_id" => "required"
        ]);

        $category = Category::findOrFail($request->category_id);

        if($request->hasFile('cover')){
            $advice = new Advice;
            $result = $request->file('cover')->store("public/advice_covers");
            $cover_path = substr($result, 7);
            $advice->title = $request->title;
            $advice->views = "0";
            $advice->likes = "0";
            $advice->category_id = $category->id;
            $advice->category_text = $category->title;
            $advice->category_image = $category->image;
            $advice->owner_id = "2";
            $advice->owner_name = "Admin";
            $advice->content = $request->content;
            $advice->cover = "{$newURL}storage/{$cover_path}";
            $advice->cover_path = $result;
            $advice->save();
        }else{
            $advice = new Advice;
            $advice->title = $request->title;
            $advice->views = "0";
            $advice->likes = "0";
            $advice->category_id = $category->id;
            $advice->category_text = $category->title;
            $advice->category_image = $category->image;
            $advice->owner_id = "2";
            $advice->owner_name = "Admin";
            $advice->content = $request->content;
            $advice->save();
        }

        return redirect()->route("advice.index")->with("success", "advice created successfully");
    }

    public function edit($id)
    {
        $advice = Advice::findOrFail($id);
        $categories = Category::all();
        return view("advice.edit", compact("advice", "categories"));
    }

    public function update(Request $request, $id)
    {
        $cover_path = null;
        $currentURL = url()->current();
        $newURL = str_replace("advice/{$id}", "", $currentURL);

        $request->validate([
            "title" => "required|unique:advice,title," . $id,
            "category_id" => "required"
        ]);

        $category = Category::findOrFail($request->category_id);
        
        if($request->hasFile('cover')){
            $advice = advice::findOrFail($id);
            if (isset($advice->cover_path)){
                Storage::delete($advice->cover_path);
            }
            $result = $request->file('cover')->store("public/advice_covers");
            $cover_path = substr($result, 7);
            $advice->title = $request->title;
            $advice->content = $request->content;
            $advice->category_id = $category->id;
            $advice->category_text = $category->title;
            $advice->category_image = $category->image;
            $advice->cover = "{$newURL}storage/{$cover_path}";
            $advice->cover_path = $result;
            $advice->save();
        }else{
            $advice = advice::findOrFail($id);
            $advice->title = $request->title;
            $advice->content = $request->content;
            $advice->category_id = $category->id;
            $advice->category_text = $category->title;
            $advice->category_image = $category->image;
            $advice->save();
        }

        return redirect()->route("advice.index")->with("success", "advice updated successfully");
    }

    public function destroy($id)
    {
        $advice = advice::findOrFail($id);
        if (isset($advice->cover_path)){
            Storage::delete($advice->cover_path);
        }
        $advice->delete();

        Session::flash("success", "advice deleted successfully");
        return response()->json(["success" => true]);
    }
}
