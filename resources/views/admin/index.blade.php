<x-app-layout>
    <div class="py-12" x-data="stateProduct">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- start alert info --}}
                    <template x-if="listProduct == 0 && isShowCard == 'table' ">
                        <div class="p-4 mb-4 text-sm text-black bg-blue-300 rounded-lg" role="alert">
                            <span class="font-medium" x-text="listProduct == 0 ? 'data product belum tersedia': '' "></span>
                        </div>
                    </template>
                    {{-- end alert info --}}

                    {{-- <template x-if="messages.success !== ''"> --}}
                        <div
                            x-show="messages.success !== ''"
                            x-transition:enter="transition ease-out duration-300"
                            class="p-4 mb-4 text-sm text-black bg-green-300 rounded-lg" role="alert">
                            <span class="font-medium" x-text="messages.success"></span>
                        </div>
                    {{-- </template> --}}


                    {{-- start tombol tambah --}}
                     <button x-show="isShowCard == 'table'" x-on:click="btnCreate()" type="button" class="rounded-lg inline-flex items-center  text-white bg-blue-400 hover:bg-blue-400 box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                        <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                        product
                    </button>
                    {{-- end tombol tambah --}}

                    {{-- start form create product --}}
                     <!-- Modal content -->
                    <div x-show="isShowCard == 'create'" class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">

                        <!-- Modal body -->
                       @include('admin._card_form_product')
                    </div>
                    {{-- end form create product --}}

                    {{-- start table product --}}
                    <div x-show="isShowCard == 'table' " class="relative mt-2 overflow-x-auto  shadow-xs rounded-base border border-default">
                        @include('admin._card_table_product')
                    </div>
                    {{-- end table product --}}

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('stateProduct', () => ({
                // membuat variable array untuk menampung seluruh data product dari BE
                listProduct: [],

                // membuat variable penampung nilai untuk nama component
                isShowCard: 'table',

                // membuat objek product
                product: {name: '', quantity: '', price: '', size: '', description: ''},

                // membuat objek errors
                errors: {name: '', quantity: '', price: '', size: '', description: ''},

                // membuat 1 variable objek untuk menampung nilai original
                originalProductStock: { name: '', stock: {}, price: '', size: '', description: '' },

                // membuat 1 variable objek untuk menampung nilai original
                editProductStock: { name: '', stock: {}, price: '', size: '', description: '' },

                // membuat objek listSize
                listSize: {kecil: 'kecil', sedang: 'sedang', besar: 'besar'},

                // membuat object message untuk menampung pesan dari BE
                messages: {info: '', success: ''},

                // membuat fungsi tambah product
                btnCreate() {
                    // menampilkan card create product
                    this.isShowCard = 'create'
                },
                btnCancelCreate() {
                    // mengambil sebagian data dari field yang ada
                    let someData = Object.values(this.product).some(value => value != "")
                    if(someData) {
                        let confirmation = confirm('yakin batal?')

                        // jika tidak ada konfirmasi kembali keawal
                        if(!confirmation) return

                        // jika ada konfirmasi batal, akan membersihkan seluruh field, dan error
                        this.resetErrors()

                        this.resetField()

                        // tampilkan card table
                        this.isShowCard = 'table'
                    }
                    // membersihkan seluruh field yang error
                    this.resetErrors()

                    // mengembalikan ke table product
                    this.isShowCard = 'table'
                },
                init() {
                    this.getListProduct()
                },
                // fungsi untuk mengambil data dari BE
                async getListProduct() {
                    try {
                        // mengambil data dari melalui nama jalur list-product
                        let result = await axios.get('list-product')

                        // memberi nilai dari BE kedalam var listProduct
                        this.listProduct = result.data.response

                    } catch (error) {
                        console.log('error', error)
                    }
                },

                resetField() {
                    Object.assign(this.product, {
                        name: '', quantity: '', price: '', size: '', description: ''
                    })
                },

                resetErrors() {
                    Object.assign(this.errors, {
                        name: '', quantity: '', price: '', size: '', description: ''
                    })
                },

                async sendDataProduct() {
                    try {
                        // reset seluruh error
                        this.resetErrors()

                        // mengumpulkan seluruh data
                        let sendDataNewProduct = {
                            name: this.product.name,
                            quantity: this.product.quantity,
                            price: this.product.price,
                            size: this.product.size,
                            description: this.product.description
                        }

                        // mengirim ke url store
                        let result = await axios.post('store-product', sendDataNewProduct)

                        // reset seluruh field
                        this.resetField()

                        // menampilkan card table
                        this.isShowCard = 'table'

                        // memasukkan isi message dari BE
                        this.messages.success = result.data.message

                        // melakukan reset message success menggunakan setTimeout
                        setTimeout(() => { this.messages.success = '' }, 5000);

                        // reload data product
                        this.getListProduct()
                    } catch (error) {
                        // mereset error terlebih dahulu
                        this.resetErrors()

                        // mengambil response error dari BE
                        let responseBe = error.response.data.errors

                        // melakukan pengecekan jika response dan response statusnya 422
                        if(error.response && error.response.status == 422) {
                            // membongkar pesan erorr yang dikirim dari BE
                            for(field in responseBe) {
                                this.errors[field] = responseBe[field][0]
                            }
                        }else {
                            console.log(error.response.data)
                        }
                    }
                },

                async btnDelete(productId) {
                    try {
                        // membuat confirmation
                        let confirmation = confirm('yakin dihapus?')

                        // jika tidak ada confirmation, kembalikan keawal
                        if(!confirmation) return

                        // mengirimkan data product id ke url product/01/delete
                        let result = await axios.delete(`product/${productId}/delete`)

                        // mengambil pesan dari BE untuk ditampilkan
                        this.messages.success = result.data.message

                        // melakukan reset messages.success menjadi kosong
                        setTimeout(()=> { this.messages.success = '' }, 4000)

                        // melakukan reload data table
                        this.getListProduct()
                    } catch (error) {
                        console.log('error', error)
                    }
                },

                async btnEdit(productId) {
                    try {
                        // mengambil data productId ke BE dengan jalur product/25/edit
                        let result = await axios.get(`product/${productId}/edit`)

                        // memasukkan data objek product kedalam variable baru
                        let response = result.data.response
                        console.log(response)

                    } catch (error) {
                        console.log(error)
                    }
                }
            }))
        })
    </script>
</x-app-layout>
