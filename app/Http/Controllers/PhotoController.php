<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\User;
use App\Models\LikedPhoto;

class PhotoController extends Controller
{
    public function showAll(Request $request)
    {
        $photo = Photo::all();
        return response()->json($photo);
    }

    public function postPhoto(Request $request)
    {
        $this->validate($request, [
            'photos' => 'required|image|max:1999',
            'caption' => 'required',
            'tags' => 'required'
        ]);
        $original_filename = $request->file('photos')->getClientOriginalName();
        $original_filename_arr = explode('.', $original_filename);
        $file_ext = end($original_filename_arr);
        $destination_path = './upload_photos/';
        $image = 'U-' . time() . '.' . $file_ext;

        if ($request->file('photos')->move($destination_path, $image)) {
            $image = 'upload_photos/' . $image;
            $datas = ['message' => 'success', 'data' => $image];
        } else {
            $response = ['message' => 'Error at upload image', 'code' => 500];
            return response()->json($response, 500);
        }
        $uploader = User::where('token', $request->header('token'))->first();
        $photo_data = Photo::create([
            'image' => $image,
            'caption' => $request->caption,
            'tags' => $request->tags,
            'uploader' => $uploader->email
        ]);
        $response = [
            'message' => 'success create data',
            'data' => $photo_data,
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getDetails($id)
    {
        $photos = Photo::find($id);
        $message = empty($photos) ? 'Data not Found' : 'Found data';
        $total_liked = LikedPhoto::where('photo_id', $id)->count();
        $photos->total_liked = $total_liked;
        $data = [
            'message' => $message,
            'data' => $photos
        ];
        return response()->json($data);
    }

    public function updateDetails($id, Request $request)
    {
        $this->validate($request, [
            'caption' => 'required',
            'tags' => 'required'
        ]);
        $photos = Photo::find($id);
        if (empty($photos)) {
            return response()->json([
                'message' => 'data not found',
                'code' => 404
            ]);
        }

        $photos->caption = $request->caption;
        $photos->tags = $request->tags;
        $photos->save();

        return response()->json([
            'message' => 'success update',
            'data' => $photos
        ]);
    }

    public function Delete($id)
    {
        $photos = Photo::find($id);
        if (empty($photos)) {
            return response()->json([
                'message' => 'data not found',
                'code' => 404
            ]);
        }

        $image = "./" . $photos->image;
        if (file_exists($image)) {
            unlink($image);
        } else {
            return response()->json([
                'message' => 'image not exist or lost',
                'code' => 404
            ]);
        }

        $liked_photo = LikedPhoto::where('photo_id', $photos->id);
        if (!empty($liked_photo->get())) {
            $liked_photo->delete();
        }
        $photos->delete();


        return response()->json([
            'message' => 'success deleted data',
            'code' => 200
        ]);
    }
}
