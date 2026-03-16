function stateProduct() {
    return {
        // variable menampung view
        isCurrentView: 'table',

        // variable array penampung seluruh list product
        listProduct: [],

        // variable menampung object product
        product: {name: '', quantity: '', price: '', size: '', description: '' },

        // variable menampung data original seluruh object product beserta data stocks
        originalProductStock:{name: '', stock: {}, price: '', size: '', description: ''},

        // variable menampung data baru ketika ada diedit di seluruh field product
        editProductStock: {name: '', stock: {}, price: '', size: '', description: ''},

        // variable menampung object error
        errors: {name: '', quantity: '', price: '', size: '', description: '' },

        // variable pemberi nilai untuk bagian select size
        listSize: {kecil: 'kecil', sedang: 'sedang', besar: 'besar'},

        // variable penampung messages
        messages: {error: '', success: '', info: ''},

        // variable menampung kondisi proses validasi ataupun pengiriman data ke BE
        isProcess: false,

        init(){
            this.getListProduct()
        },
        btnCancel() {
            // mengambil sebagian data product yang ada didalam field
            let productExist = Object.values(this.product).some(value => value !=='')
            if(productExist) {
                // membuat alert confirm
                let confirmation = confirm('yakin membatalkan?')
                if(!confirmation){
                    return
                }
                this.resetField()
                this.isCurrentView = 'table'
            }
            // reset seluruh errors bila ada
            this.resetErrors()

            // kembali menampilkan table
            this.isCurrentView = 'table'
        },
        resetField() {
            Object.assign(this.product, {
                name: '',
                amount: '',
                price: '',
                size: '',
                description: ''
            })
        },
        resetErrors() {
            Object.assign(this.errors, {
                name: '',
                quantity: '',
                price: '',
                size: '',
                description: ''
            })
        },
        createProduct() {
            this.isCurrentView = 'create'
        },
        async editStock(productId) {
            try {
                // mengambil data product melalui url
                let result = await axios.get(`product/${productId}/stock`)

                // mengambil data dari hasil BE
                let response = result.data.response

                // cloning / mengcopy data
                let clone = structuredClone(response)

                // casting / konversi tipe data dari string ke number
                clone.stock.quantity = Number(clone.stock.quantity)

                // menangkap inputan diblade
                this.editProductStock = clone

                // data cloning original dimemori
                this.originalProductStock = JSON.parse(JSON.stringify(clone))

                // menampilkan component edit
                this.isCurrentView = 'edit'

            } catch (error) {
                console.log('error disini', error)
            }
        },

        async updateStock(productId) {
            try {
                // memberi nilai untuk kondisi proses ke BE
                this.isProcess = true

                // mengumpulkan seluruh data perubahan kedalam objek
                let sendUpdateProductStock = {
                    productId: this.editProductStock.id,
                    quantity: this.editProductStock.stock.quantity,
                }

                // mengirim data ke BE melalui uri
                let result = await axios.patch(`product/${productId}/restock`, sendUpdateProductStock);

                // mengembalikan nilai awal is proses
                this.isProcess = false

                // mengambil nilai data message yang dikiirm dari BE
                this.messages.success = result.data.message

                // setting waktu untuk menghapus data message
                setTimeout(()=> {this.messages.success = ''}, 5000)

                // mengembalikan ke komponent table
                this.isCurrentView = 'table'
            } catch (error) {
                console.log('error', error)
            }
        },
        handleCancelEdit() {
            let changed = JSON.stringify(this.editProductStock) != JSON.stringify(this.originalProductStock)
            if (changed) {
                let confirmation = confirm('yakin membatalkan?')
                if(!confirmation) return
            }
            this.isCurrentView = 'table'
        },
        async getListProduct() {
            try {
                // mengambil seluruh data product melalui route list-product
                let result = await axios.get('list-product')

                // memasukkan kiriman data response dari BE
                this.listProduct = result.data.response
                console.log('data dari BE',this.listProduct)
            } catch (error) {
                console.log('error dari BE',error.response)
            }
        },
        async btnSubmit() {
            try {
                // menimpa nilai is process dengan nilai true
                this.isProcess = true

                // mengosongkan terlebih seluruh errors
                this.resetErrors()

                // mengumpulkan seluruh data product kedalam objek
                let newDataProduct = {
                    name: this.product.name,
                    quantity: this.product.quantity,
                    price: this.product.price,
                    size: this.product.size,
                    description: this.product.description,
                }
                // mengirim data ke back end dengan jalur store-product dan membawa data product baru
                let result = await axios.post('store-product', newDataProduct)

                // mengambil response hasil pengiriman dari BE
                this.messages.success = result.data.message

                // setting waktu sebanyak 10 detik  agar dapat menghapus messages.success
                setTimeout(()=> {this.messages.success = ''}, 4000)

                // reload data 
                this.getListProduct()

                // mengembalikan nilai isProcess dengan nilai false, karna sudah selesai proses validasi
                this.isProcess = false

                // kembali ke tampilan table
                this.isCurrentView = 'table'
            } catch (error) {
                this.resetErrors()

                if(error.response && error.response.status == 442) {
                    let responseErrorsBe = error.response.data.errors

                    // membongkar isi responseErrorsBe
                    for(let field in responseErrorsBe) {
                        this.errors[field] = responseErrorsBe[field][0]
                        this.isProcess = false
                    }
                }
                // if(error.response && error.response.data.status == 442) {
                //     // mengambil data responseErrorBe
                // }else {
                //     console.log(error)
                // }
            }
        },
        async btnDelete(productId) {
            try {
                // membuat confirmation
                let confirmaton = confirm('yakin menghapus?')

                // mengecek kondisi bila tidak ada confirmation
                if(!confirmaton) return

                // mengirim productId melalui uri
                let result = await axios.delete(`product/${productId}`)

                // mengambil response dari BE
                this.messages.success = result.data.message

                // setting waktu untuk menghapus pesan
                setTimeout(()=>{this.messages.success = ''}, 5000)

                // melakukan refresh / reload data  product
                this.getListProduct()

            } catch (error) {
                console.log(error)
            }
        }

    }
}
