<?php

namespace App\Http\Controllers;

use App\Models\Dvd;
use App\Http\Resources\DvdResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DvdController extends Controller
{
    /**
     * ===========1================
     * Buat fungsi index yang mengembalikan semua data dvd
     */
    public function index()
    {
        // ambil semua data dvd
        $dvd = Dvd::all();

        // return koleksi dvd
        return DvdResource::collection($dvd);
    }

    /**
     * ===========2================
     * Buat fungsi store untuk menambahkan data dvd baru
     */
    public function store(Request $request)
    {
        // Request body berisi title, director dan year
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'director' => 'nullable|string',
            'year' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat data dvd
        $dvd = Dvd::create($validator->validated());

        // return dvd yang dibuat sebagai resource
        return (new DvdResource($dvd))
                    ->additional(['message' => 'Item created successfully'])
                    ->response()
                    ->setStatusCode(201);
    }

    /**
     * ===========3================
     * Buat fungsi show untuk menampilkan satu data dvd berdasarkan ID
     */
    public function show(string $id)
    {
        // Cari data dvd berdasarkan ID
        $dvd = Dvd::find($id);

        if (!$dvd) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        // return dvd sebagai resource
        return new DvdResource($dvd);
    }

    /**
     * ===========4================
     * Buat fungsi update untuk mengubah data dvd yang ada
     */
    public function update(Request $request, string $id)
    {
        // Request body berisi title, director dan year
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'director' => 'sometimes|nullable|string',
            'year' => 'sometimes|required|integer|min:0',
        ]);

        // Cari data dvd berdasarkan ID
        $dvd = Dvd::find($id);

        if (!$dvd) {
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

        // Update data dvd
        $dvd->update($validator->validated());

        // return dvd yang diupdate sebagai resource
        return (new DvdResource($dvd))
                    ->additional(['message' => 'Item updated successfully'])
                    ->response()
                    ->setStatusCode(200);
    }

    /**
     * ===========5================
     * Buat fungsi destroy untuk menghapus data dvd
     */
    public function destroy(string $id)
    {
        // Cari data dvd berdasarkan ID
        $dvd = Dvd::item($id);

        if (!$dvd) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        // Hapus data dvd
        $dvd->delete();

        // return message sukses
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
}
