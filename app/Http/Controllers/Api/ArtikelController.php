<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\M_artikel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    //
    public function DataArtikel()
    {

        $data = M_artikel::with('user_id')->get();

        return response()->json([
            'success' => true,
            'message' => 'sukses',
            'data' => $data
        ]);
    }

    public function InsertArtikel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_artikel' => 'required',
            'isi_artikel' => 'required',
            'tanggal' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'data' => $validator->errors()
            ]);
        }

        // $user = Auth::user();
        $user = auth()->user();
        $artikel = new M_artikel;
        $artikel->judul_artikel = $request->judul_artikel;
        $artikel->isi_artikel = $request->isi_artikel;
        $artikel->tanggal = date('Y-m-d', strtotime($request->tanggal));
        $artikel->user_id = $user->id;


        $artikel->save();

        $success['judul_artikel'] = $request->judul_artikel;
        $success['isi_artikel'] = $request->isi_artikel;
        $success['tanggal'] = date('Y-m-d', strtotime($request->tanggal));

        return response()->json([
            'success' => ['user_id' => $user->id],
            'message' => 'Berhasil Diterbitkan',
            'data' => $artikel
        ]);
    }

    public function DeletedArtikel($id)
    {
        $delete = M_artikel::where('id_aritkel', $id)->delete();

        if ($delete == true) {
            return response()->json([
                'success' => true,
                'message' => 'Data terhapus',
                'data' => null
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak dapat di hapus',
                'data' => null
            ]);
        }
    }

    public function DetailArtikel(Request $request)
    {

        $id = $request->input('filter');

        $data  = M_artikel::where('id_aritkel', $id)->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ]);
        }

        // $post = M_artikel::find($id);


        // return response()->json([
        //     'success' => true,
        //     'message' => 'data berhasil ditampilkan',
        //     'data' => $post
        // ]);
    }

    public function UpdateArtikel(Request $request, $id)
    {
        // $id = $request->input('filter');
        // return $id;

        $validator = Validator::make($request->all(), [
            'judul_artikel' => 'required',
            'isi_artikel' => 'required',
            'tanggal' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $data  = M_artikel::where('id_aritkel', $id);
        if ($data) {
            $data->update([
                'judul_artikel' => $request->judul_artikel,
                'isi_artikel' => $request->isi_artikel,
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            ]);

            $success['judul_artikel'] = $request->judul_artikel;
            $success['isi_artikel'] = $request->isi_artikel;
            $success['tanggal'] = date('Y-m-d', strtotime($request->tanggal));

            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jangan dipaksaedit oi',
                'data' => $data
            ]);
        }
    }
}
