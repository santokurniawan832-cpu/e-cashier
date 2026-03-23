<?php

// penamaan nama folder
namespace App\Http\Controllers;

// registrasi nama model Product
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, DB};

class AdminController extends Controller
{
    // membuat action untuk mengambil data list product
    public function getListProduct() {
        try {
            // mengambil seluruh product dengan stock melalui class Product
            $listProduct = Product::with('stock')->get();
            // mengembalikan data respose yang diharapkan
            return response()->json([
                'message'  => 'get list product succesfully',
                'response'  => $listProduct
            ]);

        } catch (\Exception $error) {
            // mengembalikan response error
            return response()->json([
                'error' => $error->getMessage()
            ]);
        }
    }

    public function store(Request $request) {
        try {
            // melakukan validasi request inputan
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:products|max:255',
                'quantity'=> 'required',
                'size' => 'required',
                'price'=> 'required',
                'description'=> 'required'
            ],[
                'name.required' => 'nama wajib diisi..',
                'quantity.required' => 'jumlah wajib diisi..',
                'size.required' => 'ukuran wajib dipilih..',
                'price.required' => 'harga wajib diisi..',
                'description.required' => 'keterangan wajib diisi..',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors'  => $validator->errors()
                ], 422);
            }

            // membuat request pengiriman menjadi array
            $validated = $validator->validated();


            // membuat data product baru
            $product = Product::create([
                'name'  => $validated['name'],
                'size'  => $validated['size'],
                'price'  => $validated['price'],
                'description'  => $validated['description']
            ]);

            // mengambil id terakhir dibuat
            $productId = DB::getPdo()->lastInsertId();

            // membuat data stocks
            DB::insert('INSERT INTO stocks (product_id, quantity, created_at, updated_at) values (?, ?, ?, ?)', [
                $productId, $validated['quantity'], now(), now()
            ]);

            return response()->json([
                'message'  => 'produk berhasil dibuat',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message'  => $th->getMessage()
            ], 500);
        }
    }

    public function deleteProduct($productId) {
        try {
            // menemukan data product melalui productId
            $product = Product::findOrFail($productId);

            // menghapus data stock melalui objek product
            if($product->stock) {
                $product->stock->delete();
            }

            // menghapus data product
            $product->delete();

            // mengembalikan pesan response ke FE
            return response()->json([
                'message'  => 'produk dan stok berhasil dihapus..'
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
    }

    // fungsi untuk mengambil data product berdasarkan productId
    public function getProductBy($productId) {
        try {
            // mengambil data product dengan relasi stock melalui class model Product
            $product = Product::with('stock')->findOrFail($productId);

            // mengembalikan response data product berbentuk json ke FE
            return response()->json([
                'message'   => 'get product successfully',
                'response'  => $product
            ], 200);

        } catch (\Throwable $th) {
            // mengembalikan response error berbentuk json ke FE
            return response()->json([
                'message'   => $th->getMessage()
            ], 500);
        }
    }
}
