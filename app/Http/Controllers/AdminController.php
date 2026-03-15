<?php

// penamaan nama folder
namespace App\Http\Controllers;

// registrasi nama model Product
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function store(Request $request) {
        try {
            // melakukan validasi seluruh request yang dikirim dari FE
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:products|max:255',
                'size' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'description'   => 'required'
            ], [
                'name.required' => 'Nama wajib diisi..',
                'name.unique' => 'Nama Produk sudah dipakai..',
                'size.required' => 'Ukuran produk wajib dipilih..',
                'price.required' => 'Harga produk wajib diisi..',
                'quantity.required' => 'Jumlah produk wajib dipilih..',
                'description.required' => 'Keterangan Produk wajib diisi..',
            ]);
    
            // mengembalikan errors response berbentuk json
            if ($validator->fails()) {
                return response()->json([
                    'errors'    => $validator->errors()
                ], 442);
            }
    
            // menampung seluruh data product baru kedalam array
            $validated = $validator->validated();
            // membuat data product baru
            $product = Product::create($validated);
    
            // membuat data stocks
            $product->stocks()->create(['quantity'=> $validated['quantity']]);

            // mengembalikan response berhasil berbentuk json ke FE
            return response()->json([
                'message'   => 'Produk berhasil disimpan', 
                'data'      => ''
            ], 201);

            } catch (\Exception $error) {
            // mengembalikan response error berbentuk json ke FE
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    // fungsi untuk melakukan update data stok product
    public function updateStock(Request $request) {
        try {
        // mengambil semua request dari FE
        $dataRequest = $request->only(['productId', 'quantity']);

        // Mengambil data stock berdasarkan product_id
        $stock = Stock::findOrFail($dataRequest['productId']);

        // menimpa request input quantity kedalam Model Stock
        $stock->update([
            'quantity'  => $dataRequest['quantity']
        ]);

        // mengembalikan response berbentuk json
        return response()->json([
            'message'   => 'restock produk berhasil.',
        ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
        
    }

    // fungsi untuk menghapus data product berdasarkan productId
    public function deleteProduct($productId) {
        try {
        // mengambil data product berdasarkan productId
        $product = Product::findOrFail($productId);

        // menghapus data stock product
        $product->stock->delete();
        
        // menghapus data product
        $product->delete();

        // mengembalikan response berbentuk json keFE
        return response()->json([
            'message'   => 'delete product berhasii dihapus'
        ], 200);

        } catch (\Exception $error) {
             return response()->json([
            'message'   => $error->getMessage()
        ], 500);
        }
    }

    //fungsi untuk mengambil seluruh data list product beserta dengan stock
    public function getListProduct() {
        try {
            // mengambil seluruh data product dengan relasi stocks
            $listProduct = Product::with('stock')->get();
            // mengembalikan data response berbentuk json
            return response()->json([
                'message'   => 'get list product succuessfully',
                'response'      => $listProduct
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message'  => $error->getMessage()
            ],500);
        }
    }

    public function getProductBy($productId) {
        try {
            // mengambil data product berdasarkan id
            $product = Product::with('stock')->findOrFail($productId);

            // mengembalikan response berbentuk json ke FE
            return response()->json([
                'message'   => 'get data product successfully',
                'response'      => $product
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
    }
}
