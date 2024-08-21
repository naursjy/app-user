<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RolesResource;
use Illuminate\Http\Request;
use App\Models\M_Berita;
use App\Models\M_Roles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{

    public function ListRoules()
    {
        $post = M_Roles::latest()->paginate(5);
        return new RolesResource(null, true, 'List Data Berita', $post);
    }

    public function InsertRoules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $post = M_Roles::create([
            'jenis' => $request->jenis
        ]);
        return new RolesResource(['user_id' => $user->id], true, 'Berhasil Menambahkan Data Berita', $post);
    }

    public function UpdateRoules(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'jenis'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $post = M_Roles::find($request->id);

        $post->update([

            'jenis' => $request->jenis,
        ]);
        $user = Auth::user();
        return new RolesResource(['user_id' => $user->id], true, 'Berhasil Mengubah Data Berita', $post);
    }
    public function DeleteRoules($id)
    {
        $post = M_Roles::find($id);
        $user = Auth::user();

        $post->delete();
        return new RolesResource(['user_id' => $user->id], true, 'Berhasil menghapus data Roules', null);
    }
}
