<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Banner;
use Storage;
use Session;

class BannerController extends Controller
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
        $banners = banner::all();
        return view("banner.index", compact("banners"));
    }

    public function create()
    {
        return view("banner.create");
    }

    public function store(Request $request)
    {
        $image_path = null;
        $currentURL = url()->current();
        $newURL = str_replace("banner", "", $currentURL);

        $request->validate([
            "title" => "required|unique:banner,title",
        ]);

        if($request->hasFile('image')){
            $banner = new Banner;
            $result = $request->file('image')->store("public/banner_images");
            $image_path = substr($result, 7);
            $banner->title = $request->title;
            $banner->content = $request->content;
            $banner->image = "{$newURL}storage/{$image_path}";
            $banner->image_path = $result;
            $banner->save();
        }else{
            $banner = new Banner;
            $banner->title = $request->title;
            $banner->content = $request->content;
            $banner->save();
        }

        return redirect()->route("banner.index")->with("success", "banner created successfully");
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view("banner.edit", compact("banner"));
    }

    public function update(Request $request, $id)
    {
        $image_path = null;
        $currentURL = url()->current();
        $newURL = str_replace("banner/{$id}", "", $currentURL);

        $request->validate([
            "title" => "required|unique:banner,title," . $id,
        ]);

        
        
        if($request->hasFile('image')){
            $banner = Banner::findOrFail($id);
            if (isset($banner->image_path)){
                Storage::delete($banner->image_path);
            }
            $result = $request->file('image')->store("public/banner_images");
            $image_path = substr($result, 7);
            $banner->title = $request->title;
            $banner->content = $request->content;
            $banner->image = "{$newURL}storage/{$image_path}";
            $banner->image_path = $result;
            $banner->save();
        }else{
            $banner = Banner::findOrFail($id);
            $banner->title = $request->title;
            $banner->content = $request->content;
            $banner->save();
        }

        return redirect()->route("banner.index")->with("success", "banner updated successfully");
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if (isset($banner->image_path)){
            Storage::delete($banner->image_path);
        }
        $banner->delete();

        Session::flash("success", "banner deleted successfully");
        return response()->json(["success" => true]);
    }
}
