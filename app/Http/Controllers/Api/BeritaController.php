<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeritaResource;
use Illuminate\Http\Request;
use App\Models\M_Berita;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BeritaController extends Controller
{
    //

    // public function DataView()
    // {
    //     $post = M_Berita::with('user_id')->latest()->paginate(5);
    //     return new BeritaResource('Its Can Work', true, 'List Data Berita', $post);
    // }

    public function DataView()
    {
        $post = M_Berita::select('id', 'title', 'subtitle', 'content', 'image', 'user_id')
            ->with('user')
            ->latest()
            ->paginate(5);
        return new BeritaResource('Its Can Work', true, 'List Data Berita', $post);
    }

    public function InsertBerita(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'subtitle'   => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());
        $user = Auth::user();
        $post = M_Berita::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->content,
            'user_id' => Auth::user()->id,
        ]);
        return new BeritaResource('id', ['user_id' => $user->id], 'Berhasil Menambahkan Data Berita', $post);
    }
    public function Detail_Berita($id)
    {
        $post = M_Berita::find($id);
        $user = Auth::user();
        if ($user) {
            return new BeritaResource(['user_id' => $user->id], true, 'Berhasil Menambahkan Data Berita', $post);
        }
    }

    public function UpdateBerita(Request $request)
    {
        $user = Auth::user();
        // if ($user->id !== 1) { // hanya user_id 1 yang bisa CUD
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        //masih mencari cara untuk menentukan superadmin dan admin

        $validator = Validator::make($request->all(), [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg',
            'title'     => 'required',
            'subtitle'   => 'required',
            'content'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $post = M_Berita::find($request->id);
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts', basename($post->image));

            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'content' => $request->content,
                'user_id' => Auth::user()->id,
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'subtitle' => $request->subtitle,
            ]);
        }
        if (!$post->user_id) {
            $post->update(['user_id' => $user->id]);
        }

        return new BeritaResource(['user_id' => $user->id], true, 'Berhasil Menambahkan Data Berita', $post);
    }

    public function DeleteBerita($id)
    {
        $post = M_Berita::find($id);
        $user = Auth::user();

        Storage::delete('public/posts/' . basename($post->image));

        $post->delete();
        return new BeritaResource(['user_id' => $user->id], true, 'Berhasil menghapus data berita', null);
    }
}
