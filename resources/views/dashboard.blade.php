<x-app-layout>
    <div class="py-12"
        {{-- membuat state komponent alpine --}}
        x-data="stateListProduct()" x-cloak>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- {{ __("CASHIER DASHBOARD") }} --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 item-start">
                        {{-- start card list product --}}
                        @include('cashier._card_list_product')
                        {{-- end card list product --}}

                        {{-- start card order product --}}
                        <div class="scale-100 p-6 dark:bg-gray-400 rounded-lg shadow-md flex flex-col sticky top-6 h-fit max-h-[80vh] overflow-y-auto">
                            @include('cashier._card_order_product')
                        </div>
                        {{-- end card order product --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{asset('js/stateListProduct.js')}}"></script>
    @endpush
</x-app-layout>
