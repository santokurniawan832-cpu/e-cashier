<x-app-layout>
    <div class="py-12" >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <form id="editForm" action="{{ route('product.update.restock', $product->id)}}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
                            <div class="col-span-2 sm:col-span-1">
                                <input type="hidden" name="product_id" value="{{$product->id}}"/>
                                <label for="name" class="block mb-2.5 text-sm font-medium text-heading">Nama</label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $product->name }}"
                                    x-model="product.name"
                                    id="name"
                                    class="cursor-not-allowed rounded-lg bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    placeholder="Ketikan nama produk"
                                    readonly
                                >
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                             <div class="col-span-2 sm:col-span-1">
                                <label for="category" class="block mb-2.5 text-sm font-medium text-heading">Jumlah</label>
                                {{-- @foreach ($product->stocks as $stock)
                                    <select
                                        id="category"
                                        name="stocks[{{ $stock->id }}][quantity]"
                                        class="rounded-lg block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ $stock->quantity == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                @endforeach --}}
                                 <select name="quantity" value="{{$product->quantity}}" id="category" class="rounded-lg block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                                    @for ($i = 0; $i < 10; $i++)
                                        <option value="{{ $i + 1 }}">{{ $i + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="price" class="block mb-2.5 text-sm font-medium text-heading">Harga</label>
                                <input
                                    type="number"
                                    name="price"
                                    value="{{ $product->price }}"
                                    id="price"
                                    class="cursor-not-allowed rounded-lg bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    placeholder="Rp.1000"
                                    readonly
                                >
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="category" class="block mb-2.5 text-sm font-medium text-heading">Ukuran</label>
                                <select value="{{ $product->size }}" id="category"  name="size" class="cursor-not-allowed rounded-lg block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                                   <option selected="{{$product->size}}">{{$product->size}}</option>
                                </select>
                                <x-input-error :messages="$errors->get('size')" class="mt-2" />
                            </div>
                            <div class="col-span-2">
                                <label for="description" class="block mb-2.5 text-sm font-medium text-heading">Keterangan</label>
                                <textarea
                                    id="description"
                                    rows="4"
                                    name="description"
                                    class="cursor-not-allowed rounded-lg block bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                                    readonly
                                    placeholder="Ketikkan keterangan produk disini">{{ $product->description }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 pt-4 md:pt-6">
                            <button type="submit" class="rounded-lg inline-flex items-center  text-white bg-blue-400 hover:bg-blue-600 box-border border border-transparent focus:ring-4 focus:ring-blue-400 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                               Simpan
                            </button>
                            <button
                                type="button"
                                onclick="handleCancel()"
                                class="rounded-lg text-body bg-gray-400 box-border border border-default-medium hover:bg-gray-200 hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const form =  document.getElementById('editForm')
        const initialData = new FormData(form);
        let isChanged = false;

        form.addEventListener('change', () => {
            const currentData = new FormData(form);
            isChanged = false;

            for (let [key, value] of currentData.entries()) {
                if (initialData.get(key) !== value) {
                    isChanged = true;
                    break;
                }
            }
        });

        function handleCancel() {
            if (isChanged) {
                if (!confirm('Yakin membatalkan perubahan?')) {
                    return;
                }
            }
            window.location.href = '/admin-dashboard';
        }
    </script>
</x-app-layout>
