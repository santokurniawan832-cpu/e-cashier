 <table class="w-full text-sm text-left rtl:text-right text-body">
    <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
        <tr>
                <th scope="col" class="px-6 py-3 font-medium">
                #
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                Nama
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                Jumlah
            </th>
                <th scope="col" class="px-6 py-3 font-medium">
                Ukuran
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                Harga
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                <span class="sr-only">Edit</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <template x-if="listProduct.length > 0">
            <template x-for="(product, index) in listProduct" :key="product.id">
                <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium">
                    <th class="px-6 py-4" x-text="index + 1"></th>
                    <td class="px-6 py-4" x-text="product.name"></td>
                    <td class="px-6 py-4">
                        <span   x-text="product.stock.quantity" x-on:click="editStock(product.id)" 
                            class="hover:underline hover:cursor-pointer hover:text-blue-400 hover:font-semibold" 
                        ></span>
                    </td>
                    <td class="px-6 py-4" x-text="product.size"></td>
                    <td class="px-6 py-4" x-text="product.price"></td>
                    <td class="px-6 py-4 text-right inline-flex gap-2">

                        <span  x-on:click="btnDelete(product.id)" 
                            class="hover:underline text-red-400 hover:cursor-pointer hover:text-red-600 hover:font-semibold" 
                        >delete</span>
                    </td>
                </tr>
            </template>
        </template>
    </tbody>
</table>
