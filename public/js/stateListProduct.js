
function stateListProduct() {
    return {
        // membuat array untuk menampung data dari backend
        listProduct: [],

        // membuat array untuk menampung data produk didalam keranjang
        listProductOnCart: [],

        // menampung data order product yang akan dibayar sesuai dengan jumlah produk yang dibeli
        dataOrderProduct: { amount: 0, order_product: [] },

        // menampung data pesan
        message: {success: '', alert: ''},

        // menampung jumlah uang pembataran
        totalHargaOrder: 0,

        // menampung kondisi proses pengiriman
        isProcess: false,

        hasFetchedProduct: false,

        init() {
            this.getListProduct()
        },
        async getListProduct() {
            this.isProcess = true
            this.hasFetchedProduct = false
            try {
                let result = await axios.get('list-product')
                this.listProduct = result.data.response

                if(this.listProduct.length == 0){
                    this.message.alert = 'data product belum tersedia.'
                }

                
                // mengosongkan list product yang ada cart
                this.listProductOnCart = []
            } catch (error) {
                console.log('error', error)
            }
            this.isProcess = false
            this.hasFetchedProduct = true
        },
        // membuat fungsi event click untuk tambah data produk kedalam keranjang
        addProductToCart(product) {
            // mengecek data produk ada atau tidak didalam keranjang
            let existProduct = this.listProductOnCart.find(item => item.id === product.id)

            // mengecek data existProduct
            if(existProduct) {
                existProduct.qty  += 1
            } else {
                // memasukkan produk kedalam keranjang
                this.listProductOnCart.push({
                    ...product, // meletakkan data produk
                    qty: 1, // qty sebagai dalam array
                    stock: product.stock.quantity ?? 0 // menampung jumlah stok dari produk
                })
            }
        },
        decrementQty(product){
           if (product.qty > 1) {
                product.qty--
           }
        },
        incrementQty(product) {
            if(product.qty < product.stock) {
                product.qty++
             }
        },
        // ketika 1 objek produk didalam
        productInCart(productId) {
            return this.listProductOnCart.some(item=> item.id == productId)
        },
        // fungsi untuk melakukan cek jumlah uang cash yang dikasi customer
        async payNow() {
            try {
            // sedang process true
            this.message.success = ''
            this.isProcess = true
            // mengambil total harga dari seluruh order
            this.totalHargaOrder = this.listProductOnCart.reduce((sum, item)=>  sum +  (item.qty * item.price), 0)

            // pengecekan jika input uang pembayara lebih kecil dari total harga
            if(this.dataOrderProduct.amount < this.totalHargaOrder) {
                this.message.alert = 'uang pembayaran tidak cukup..'
                this.isProcess = false
                return
            }

            /* mengumpulkan dataOrderProduct berdasarkan listProductOnCart
                akan menampung dalam array dataOrderProduct
                order_product sebagai key baru yang ada didalam dataOrderProduct
                didalam key order_product terdapat beberapa objek yang menjadi item product yang diorder
                didalam objek itemProduct ada product_id, price, qty
             */
            this.dataOrderProduct.order_product = this.listProductOnCart.map( itemProduct => ({
                product_id: itemProduct.id,
                price: itemProduct.price,
                qty: itemProduct.qty
            }))

            // mengirim data order produk ke BE
            let result = await axios.post('store-order', this.dataOrderProduct)

            // proses berhenti
            this.isProcess = false
            
            // mengambil pesan message
            this.message.success = result.data.message
            setTimeout(()=> {  this.message.success = '' },5000)

            // melakukan reset order product
            this.listProductOnCart = []

            // mereset data order product
            Object.assign(this.dataOrderProduct, {
                amount: 0,
                order_product: []
            })

            // reload data
            this.getListProduct()
            } catch (error) {
                console.log('error dari BE', error.response)
                this.isProcess = false
            }
        },
        removeProductFromCart(productId) {
           if(!productId) return

           // jika ada product id yang dipilih maka akan dihapus dari cart
           this.listProductOnCart = this.listProductOnCart.filter(item => item.id !== productId)

           // menghapus seluruh list dataOrderProduct
           // menghapus eror uang tidak cukup
           this.message.alert = ''

           // menghapus inputan jumlah uang yang dibayar
           this.dataOrderProduct.amount = 0
        },
    }
}
