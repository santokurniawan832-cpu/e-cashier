<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    // membuat fungsi untuk mengambil data dan mengirim ke front-end
    public function getListProduct() {
        try {
            // mengambil data produk dan relasinya
            $listProduct = Product::with('stocks')->get();

            // mengembalikan data berbentuk json
            return response()->json([
                'data'  => $listProduct,
                'message'   => 'get list product successfully'
            ], 200);
        } catch (\Exception $error) {
            //melemparkan error bila data tidak ada
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
    }

    public function exampleIndex() {
        return view('cashier.example-index');
    }

    public function exampleGetListProduct() {
        try {
            $listProdut = Product::with('stocks')->get();
            return response()->json([
                'message'   => 'get list products successfully',
                'data'      => $listProdut
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
    }

    public function exampleStoreOrderProduct(Request $request) {
         $validator = Validator::make($request->all(), [
            'total_amount' => 'required',
            'order_product' => 'required'
        ]);

        $validated = $validator->validated();

        // melakukan perulangan untuk membongkar array order_product kiriman data dari front end
        // mengisi data kedalam class product melalui relasi
        // mengirim pesan berbentuk respon json

        dd($validated);
    }

    public function storeOrderProduct(Request $request) {
        try {
            // melakukan validasi pengecekan data request
            $validated = $request->validate([
                'amount' => 'required|numeric',
                'order_product' => 'required|array',
                'order_product.*.product_id' => 'required|exists:products,id',
                'order_product.*.qty' => 'required|integer|min:1',           
            ]);

            // melakukan transaksi data yang dikirim
            DB::transaction(function () use ($validated) {

            $productIds = collect($validated['order_product'])->pluck('product_id');

            /*  mengubah menjadi data array contoh 
                 #items: array:2 [
                    0 => 24
                    1 => 23
                ]
            */

            // mengambil selurh data product dimana id nya berbentuk array yang dikirim
            $listOrderProduct = Product::with('stock')
                ->whereIn('id', $productIds)
                ->get()
                ->keyBy('id');
            
            // melakukan pembongkaran dengan cara looping
           foreach ($validated['order_product'] as $item) {

                // mengambil data product berdasarkan product_id sesuai loopingan
                $product = $listOrderProduct[$item['product_id']];

                // mengambil jumlah order per produk
                $qtyOrder = $item['qty'];

                // ambil stock product
                $stock = $product->stock;

                // mengurangkan nilai quantity
                $stock->decrement('quantity', $qtyOrder);
            }
        });

        return response()->json([
            'message' => 'Order berhasil'
        ],200);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
    }

}
