<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Item',
            'url_json' => url('items/get_data'),
            'url' => url('items'),
        ];

        return view('item', $data);
    }

    public function getData()
    {
        return respone()-json([
            'status' => true,
            'data' => Item::all(),
            'message' => 'data berhasil ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function storeData(Request $request)
    {
        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => ['required', 'unique:items', 'min:3', 'max:255'],
            'status' => ['required', 'in:1,0'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        Item::create($data);

        return respone()-json([
            'status' => true,
            'message' => 'data berhasil ditambahkan',
        ])->header('Content-Type', 'application/json')->setStatusCode(201);
    }

    public function getDataById($idItem)
    {
        $item = Item::where('id', $idItem)->first();

        if(!$item) {
            return respone()-json([
                'status' => true,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        Item::create($data);

        return respone()-json([
            'status' => true,
            'data' => $item,
            'message' => 'data berhasil ditambahkan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function updateData(Request $request, $idItem)
    {
        $item = Item::where('id', $idItem)->first();

        if(!$item) {
            return respone()-json([
                'status' => true,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => ['required',  'min:3', 'max:255', 'unique:items,item_name,' . $item->id],
            'status' => ['required', 'in:1,0'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $item->update($data);

        return respone()-json([
            'status' => true,
            'message' => 'data berhasil diubah',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function destroyData(Request $request, $idItem)
    {
        $item = Item::where('id', $idItem)->first();

        if(!$item) {
            return respone()-json([
                'status' => true,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        $item->delete();

        return respone()-json([
            'status' => true,
            'message' => 'data berhasil dihapus',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }
}
