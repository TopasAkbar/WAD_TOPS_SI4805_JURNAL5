<?php

namespace App\Http\Controllers;

use App\Models\Vinyl;
use App\Http\Resources\VinylResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VinylController extends Controller
{
    /**
     * ===========1================
     * Buat fungsi index yang mengembalikan semua data vinyl
     */
    public function index()
    {
        // ambil semua data vinyl
        // $vinyls = ....
        $vinyls = Vinyl::all(); 
        // return koleksi vinyl
        // return ....
        return VinylResource::collection($vinyls);
    }

    /**
     * ===========2================
     * Buat fungsi store untuk menambahkan data vinyl baru
     */
    public function store(Request $request)
    {
        // Request body berisi title, artist dan year
        $validator = Validator::make($request->all(), [
            'title'=>'required|string|max:255',
            'artist'=>'required|string|max:255',
            'year'=>'required|year|max:255',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'please check your request',
                'errors'=>$validator->errors()
            ], 422);
        }

        $vinyls = Vinyl::create($validator->validated());
        return(new VinylResource($vinyls))
            ->additional(['message'=>'Item created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * ===========3================
     * Buat fungsi show untuk menampilkan satu data vinyl berdasarkan ID
     */
    public function show(string $id)
    {
        // Cari data vinyl berdasarkan ID
        // $vinyl = ....
        $vinyl = Vinyl::find($id);
        if (!$vinyl) {
            return response()->json([
                'message'=>'please check your request',
                'errors'=>$validator->errors()
            ], 404);
        }

        return new VinylResource($vinyl);
    }

    /**
     * ===========4================
     * Buat fungsi update untuk mengubah data vinyl yang ada
     */
    public function update(Request $request, string $id)
    {
        // Request body berisi title, artist dan year
        $validator = Validator::make($request->all(), [
            'title'=>'required|string|max:255',
            'artist'=>'required|string|max:255',
            'year'=>'required|timestamp|max:255',
        ]);

        $vinyl = Vinyl::find($id);
        // Cari data vinyl berdasarkan ID
        // $vinyl = ....
        if (!$vinyl) {
            return response()->json([
                'message'=>'Item not found',
            ], 404);
        }


        if ($validator->fails()) {
            return response()->json([
                'message'=>'please check your request',
                'errors'=>$validator->errors()
            ], 422);
        }

        $vinyl->update($validator->validated());
        // Update data vinyl
        // $vinyl->....
        return (new VinylResource($vinyl))
            ->additional(['message'=>'Item update successfully'])
            ->response()
            ->setStatusCode(201);
        // return vinyl yang diupdate sebagai resource
        // return ....
    }

    /**
     * ===========5================
     * Buat fungsi destroy untuk menghapus data vinyl
     */
    public function destroy(string $id)
    {
        // Cari data vinyl berdasarkan ID
        // $vinyl = ....
        $vinyl = Vinyl::find($id);
        if (!$vinyl) {
            return response()->json([
                'message'=>'Item not found',
            ], 404);
        }

        $vinyl->delete();
        // Hapus data vinyl
        // $vinyl->....
        return response()->json(['messsage'=>'Item deleted successfully'], 200);
        // return message sukses
        // return ....
    }
}
