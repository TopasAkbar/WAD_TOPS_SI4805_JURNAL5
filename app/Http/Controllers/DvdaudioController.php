<?php

namespace App\Http\Controllers;

use App\Models\DvdAudio;
use App\Http\Resources\DvdaudioResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DvdaudioController extends Controller
{
    /**
     * ===========1================
     * Buat fungsi index yang mengembalikan semua data dvdaudio
     */
    public function index()
    {
        // ambil semua data dvdaudio
        // $dvdaudios = ....
        $dvdaudios = DvdAudio::all();

        // return koleksi dvdaudio
        // return ....
        return DvdaudioResource::collection($dvdaudios);
    }

    /**
     * ===========2================
     * Buat fungsi store untuk menambahkan data dvdaudio baru
     */
    public function store(Request $request)
    {
        // Request body berisi title, artist dan year
        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat data dvdaudio
        // $dvdaudio = ....
        $dvdaudio = DvdAudio::create($validator->validated());

        // return dvdaudio yang dibuat sebagai resource
        // return ....
        return (new DvdaudioResource($dvdaudio))
                    ->addictional(['message => Item Created Succsessfully'])
                    ->response()
                    ->setStatusCode(201);

    }

    /**
     * ===========3================
     * Buat fungsi show untuk menampilkan satu data dvdaudio berdasarkan ID
     */
    public function show(string $id)
    {
        // Cari data dvdaudio berdasarkan ID
        $dvdaudio = DvdAudio::find($id);


        if (!$dvdaudio) {
            return response()->json([
                'success' => false,

                // 'message' => ...
                'message' => 'Item not found'
            ], 404);
        }

        // return dvdaudio sebagai resource
        // return ....
        return new DvdaudioResource($dvdaudio);
    }

    /**
     * ===========4================
     * Buat fungsi update untuk mengubah data dvdaudio yang ada
     */
    public function update(Request $request, string $id)
    {
        // Request body berisi title, artist dan year
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max=225',
            'artist' => 'sometimes|required|string|max=225',
            'year' => 'sometimes|required|year|max=225',

        ]);

        // Cari data dvdaudio berdasarkan ID
        $dvdaudio = DvdAudio::find($id);


        if (!$dvdaudio) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data dvdaudio
        $dvdaudio-> update($validator->validated());


        // return dvdaudio yang diupdate sebagai resource
        // return ....
        return (new DvdaudioResource($dvdaudio))
                ->additional(['message'=>'Item updated successfully'])
                ->responses()
                ->setStatusCode(200);
    }

    /**
     * ===========5================
     * Buat fungsi destroy untuk menghapus data dvdaudio
     */
    public function destroy(string $id)
    {
        // Cari data dvdaudio berdasarkan ID
        $dvdaudio = DvdAudio::find($id);

        if (!$dvdaudio) {
            return response()->json([
                'message'=> 'Item not found'
            ], 404);
        }

        // Hapus data dvdaudio
        // $dvdaudio->....
        $dvdaudio ->delete();
        return response()->json(['message' => 'Item deleted succsessfully'], 300);

        // return message sukses
        // return ....
    }
}
