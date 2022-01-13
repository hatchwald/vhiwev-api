<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LikedPhoto;
use App\Models\Photo;
use App\Models\User;

class LikedPhotoController extends Controller
{
    public function LikedPhoto($id, Request $request)
    {
        $photos = Photo::find($id);
        if (empty($photos)) {
            return response()->json([
                'message' => 'data not found',
                'code' => 404
            ]);
        }
        $user = User::where('token', $request->header('token'))->first();

        $data_liked = LikedPhoto::where('user_id', $user->id)->where('photo_id', $photos->id)->first();

        if (!empty($data_liked)) {
            return response()->json([
                'message' => 'you already liked this photo',
                'code' => '400'
            ]);
        }
        $create_liked_photo = LikedPhoto::create([
            'user_id' => $user->id,
            'photo_id' => $photos->id
        ]);

        return response()->json([
            'message' => 'success liked photo',
            'code' => 200
        ]);
    }

    public function UnlikedPhoto($id, Request $request)
    {
        $photos = Photo::find($id);
        if (empty($photos)) {
            return response()->json([
                'message' => 'data not found',
                'code' => 404
            ]);
        }
        $user = User::where('token', $request->header('token'))->first();
        $data_liked = LikedPhoto::where('user_id', $user->id)->where('photo_id', $photos->id)->first();
        if (empty($data_liked)) {
            return response()->json([
                'message' => "You don't like this photo yet",
                'code' => 404
            ]);
        }
        $data_liked->delete();
        return response()->json([
            'message' => 'success unlike this photo',
            'code' => 200
        ]);
    }
}
