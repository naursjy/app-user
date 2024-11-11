<?php

namespace App\Http\Controllers;

use App\Models\M_detail;
use App\Models\M_pengaduan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PengaduanController extends Controller
{

    public function index()
    {
        $pengaduan = M_pengaduan::all();
        return response()->json($pengaduan, 500);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $pengaduan = M_pengaduan::create([
                'judul' => $request->input('judul'),
                'isi' => $request->input('isi'),
                'kategori' => $request->input('kategori'),
                'status' => 'pending'
            ]);

            // Simulate an approval process
            // if ($pengaduan->kategori === 'critical') {
            //     // If not approved, roll back the transaction
            //     DB::rollBack();
            //     return response()->json(['message' => 'Pengaduan not approved'], 422);
            // }

            // Insert additional data into a related table
            // $pengaduan->detail()->create([
            //     'keterangan' => $request->input('keterangan'),
            // ]);

            // If everything is okay, commit the transaction
            DB::commit();

            return response()->json(['message' => 'Pengaduan created successfully'], 201);
        } catch (Exception $e) {
            // If any error occurs, roll back the transaction
            DB::rollBack();
            return response()->json(['message' => 'Error occurred while creating pengaduan'], 500);
        }
    }


    //approve
    public function approvePengaduan(Request $request, $id)
    {
        $pengaduan = M_pengaduan::find($id);

        if (!$pengaduan) {
            return response()->json(['message' => 'Pengaduan not found'], 404);
        }

        if ($pengaduan->status === 'approved') {
            return response()->json(['message' => 'Pengaduan already approved'], 422);
        }
        if (!$pengaduan->approved) { // assume $approved is a boolean variable indicating approval status

            $mDetails = M_detail::where('pengaduan_id', $pengaduan->id)->get();
            $mDetails->each->delete();
            $pengaduan->delete();
            return response()->json(['message' => 'Pengaduan not approved, data deleted'], 422);
        }

        $pengaduan->status = 'approved';
        $pengaduan->save();

        return response()->json(['message' => 'Pengaduan approved successfully'], 200);
    }
}
