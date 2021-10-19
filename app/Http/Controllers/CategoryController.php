<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use Storage;
use Session;

class CategoryController extends Controller
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
        $categories = Category::all();
        return view("category.index", compact("categories"));
    }

    public function create()
    {
        return view("category.create");
    }

    public function store(Request $request)
    {
        $image_path = null;
        $currentURL = url()->current();
        $newURL = str_replace("category", "", $currentURL);

        $request->validate([
            "title" => "required|unique:category,title",
        ]);

        if($request->hasFile('image')){
            $category = new Category;
            $result = $request->file('image')->store("public/category_images");
            $image_path = substr($result, 7);
            $category->title = $request->title;
            $category->image = "{$newURL}storage/{$image_path}";
            $category->image_path = $result;
            $category->save();
        }else{
            $category = new Category;
            $category->title = $request->title;
            $category->save();
        }

        return redirect()->route("category.index")->with("success", "category created successfully");
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view("category.edit", compact("category"));
    }

    public function update(Request $request, $id)
    {
        $image_path = null;
        $currentURL = url()->current();
        $newURL = str_replace("category/{$id}", "", $currentURL);

        $request->validate([
            "title" => "required|unique:category,title," . $id,
        ]);
        
        if($request->hasFile('image')){
            $category = Category::findOrFail($id);
            if (isset($category->image_path)){
                Storage::delete($category->image_path);
            }
            $result = $request->file('image')->store("public/category_images");
            $image_path = substr($result, 7);
            $category->title = $request->title;
            $category->image = "{$newURL}storage/{$image_path}";
            $category->image_path = $result;
            $category->save();
        }else{
            $category = Category::findOrFail($id);
            $category->title = $request->title;
            $category->save();
        }

        return redirect()->route("category.index")->with("success", "category updated successfully");
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if (isset($category->image_path)){
            Storage::delete($category->image_path);
        }
        $category->delete();

        Session::flash("success", "category deleted successfully");
        return response()->json(["success" => true]);
    }
}
