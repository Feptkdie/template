<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Advice;
use App\Models\Category;
use Validator;
use Storage;
use JWTAuth;


class ApiController extends Controller
{

    public function like_advice(Request $request){
        $is_user = $this->check_user();
        $validator = Validator::make($request->all(),
        [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized', 'message' => $validator->errors()], 406);
        }

        $advice = Advice::findOrFail($request->id);
        $user = User::findOrFail(auth()->user()->id);

        if ($advice->owner_id == auth()->user()->id){
            return response()->json(['error' => 'Unauthorized', 'message' => 'You are this advices owner'], 406);
        }else{
            $user_liked_advices = $user->liked_advice_id;
            if (str_contains($user_liked_advices, $request->id)){
                return response()->json(["success" => false, "message" => "You already liked this advice"]);
            }else{
                $count = (int)$advice->likes;
                $count++;
                $advice->likes = $count;
                $advice->save();
                $user = User::findOrFail(auth()->user()->id);
                $liked_advice = "{$user->liked_advice_id},{$request->id}";
                $user->liked_advice_id = $liked_advice;
                $user->save();
                return response()->json(["success" => true, "message" => "Liked"]);
            }
           
        }
        
    }

    public function view_advice(Request $request){
        $is_user = $this->check_user();
        $validator = Validator::make($request->all(),
        [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized', 'message' => $validator->errors()], 406);
        }

        $advice = Advice::findOrFail($request->id);
        if ($advice->owner_id == auth()->user()->id){
            return response()->json(['error' => 'Unauthorized', 'message' => 'You are this advices owner'], 406);
        }else{
            $count = (int)$advice->views;
            $count++;
            $advice->views = $count;
            $advice->save();
            return response()->json(["success" => true, "message" => "Viewed"]);
        }
        
    }

    public function update_advice(Request $request){
        $is_user = $this->check_user();

        $cover_path = null;

        $validator = Validator::make($request->all(),
        [
            'id' => ['required'],
            'title' => ['required'],
            'category_id' => ['required'],
            'category_text' => ['required'],
            'category_image' => ['required'],
            'content' => ['required'],
            'url' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized', 'message' => $validator->errors()], 406);
        }

        $advice = Advice::findOrFail($request->id);

        if ($advice->owner_id == auth()->user()->id){
            if($request->hasFile('cover')){
                if (isset($advice->cover_path)){
                    Storage::delete($advice->cover_path);
                }
                $result = $request->file('cover')->store("public/advice_covers");
                $cover_path = substr($result, 7);
                $advice->cover = "{$request->url}/storage/{$cover_path}";
                $advice->cover_path = $result;
            }
            $advice->title = $request->title;
            $advice->category_id = $request->category_id;
            $advice->category_text = $request->category_text;
            $advice->category_image = $request->category_image;
            $advice->content = $request->content;
            $advice->save();
        }else{
            return response()->json(['error' => 'Unauthorized', 'message' => 'Your token not match the advice owner'], 401);
        }
        return response()->json(['status' => true, 'message' => "Uploaded"]);
    }

    public function delete_advice(Request $request){
        $is_user = $this->check_user();
        $validator = Validator::make($request->all(),
        [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized', 'message' => $validator->errors()], 406);
        }

        $advice = Advice::findOrFail($request->id);
        if ($advice->owner_id == auth()->user()->id){
            if (isset($advice->cover_path)){
                Storage::delete($advice->cover_path);
            }
            $advice->delete();
    
            return response()->json(["success" => true, "message" => "Deleted"]);
        }else{
            return response()->json(['error' => 'Unauthorized', 'message' => 'Your token not match the advice owner'], 406);
        }
        
    }
    
    public function upload_advice(Request $request){
        
        $is_user = $this->check_user();

        $cover_path = null;

        $validator = Validator::make($request->all(),
        [
            'title' => ['required'],
            'category_id' => ['required'],
            'category_text' => ['required'],
            'category_image' => ['required'],
            'content' => ['required'],
            'url' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized', 'message' => $validator->errors()], 406);
        }

        $advice = new Advice;
        if($request->hasFile('cover')){
            $result = $request->file('cover')->store("public/advice_covers");
            $cover_path = substr($result, 7);
            $advice->cover = "{$request->url}/storage/{$cover_path}";
            $advice->cover_path = $result;
        }
        $advice->title = $request->title;
        $advice->views = "0";
        $advice->likes = "0";
        $advice->category_id = $request->category_id;
        $advice->category_text = $request->category_text;
        $advice->category_image = $request->category_image;
        $advice->owner_id = auth()->user()->id;
        $advice->owner_name = auth()->user()->name;
        $advice->owner_avatar = auth()->user()->avatar;
        $advice->content = $request->content;
        $advice->save();
        
        return response()->json(['status' => true, 'message' => "Uploaded"]);
    }

    public function update_user_avatar(Request $request){

        $is_user = $this->check_user();

        $cover_path = null;

        $validator = Validator::make($request->all(),
        [
            'url' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized', 'message' => $validator->errors()], 406);
        }

        $user = User::findOrFail(auth()->user()->id);

        if (isset($user->avatar_path)){
            Storage::delete($user->avatar_path);
        }
        

        if($request->hasFile('avatar')){
            $result = $request->file('avatar')->store("public/user_avatars");
            $cover_path = substr($result, 7);
            $user->avatar = "{$request->url}/storage/{$cover_path}";
            $user->avatar_path = $result;
            $user->save();
        }

        return response()->json(['status' => true,'message' => 'Uploaded', "avatar"=> $user->avatar]);
    }

    public function get_data(){
        
        // Get all data
        $advices = Advice::all();
        $categories = Category::all();

        // Creating temp array
        $r_items = array();

        $i = 0;
        $j = 0;

        foreach($categories as $category){
            $r_items[$i]["id"] = $category->id;
            $r_items[$i]["title"] = $category->title;
            $r_items[$i]["image"] = $category->image;
            $j = 0;
            foreach($advices as $advice){
                if ($category->id == $advice->category_id){
                    $r_items[$i]["advice"][$j] = $advice;
                    $j++;
                }
            }
            $i++;
        }

        return response()->json(['status' => true,'message' => 'Found data', "data"=> $r_items]);
    }

    private function check_user(){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

        }
    }
}
