<x-app-layout>
    <div class="py-12" x-data="stateProduct()" x-cloak>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- alert info data product kosong --}}
                    <template x-if="listProduct == 0 && isCurrentView =='table'">
                        <div class="p-4 mb-4 text-sm text-blue-400 rounded-lg bg-blue-800" role="alert">
                            <span class="font-medium" x-text="listProduct == 0 ? 'data produk belum ada.': ''"></span>
                        </div>
                    </template>
                    {{-- start alert info data product kosong --}}
                    {{-- tombol add product --}}
                    <button  x-show="isCurrentView == 'table'" x-on:click="createProduct()"
                    class="inline-flex items-center mb-2 rounded-lg text-white bg-blue-400 box-border border border-transparent hover:bg-blue-400-600 focus:ring-4 focus:ring-blue-200 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
                    <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                        produk
                    </button>
                    <template x-if="messages.success">
                         <div
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2" class="p-4 mb-4 text-sm text-white font-semibold rounded-lg bg-green-500" role="alert">
                            <span class="font-medium" x-text="messages.success"></span>
                        </div>
                    </template>


                    {{-- datatable --}}
                    <div x-show="isCurrentView =='table'"  class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                       @include('admin._card_table')
                    </div>

                    {{-- start create product --}}
                    <div x-show="isCurrentView =='create'">
                        @include('admin._card_create')
                    </div>
                    {{-- end create product --}}

                    {{-- start edit product stock --}}
                    <div x-show="isCurrentView == 'edit'">
                        @include('admin._card_edit')
                    </div>
                    {{-- end edit product stock --}}
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{asset('js/stateProduct.js')}}"></script>
    @endpush
</x-app-layout>
