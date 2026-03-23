<table class="w-full text-sm text-left rtl:text-right text-body">
    <thead class="text-sm text-body bg-gray-400 border-b border-default-medium">
        <tr>
                <th scope="col" class="px-6 py-3 font-medium">
                #
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                nama produk
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                jumlah
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                ukuran
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                harga
            </th>
            <th scope="col" class="px-6 py-3 font-medium">
                <span class="sr-only">Edit</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <template x-if="listProduct.length > 0">
            <template x-for="(product,index) in listProduct" :key="product.id">
                <tr class="bg-gray-200 border-b border-default hover:bg-gray-300">
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap" x-text="index + 1"></th>
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap" x-text="product.name"></th>
                    <td class="px-6 py-4 cursor-pointer hover:underline" >
                        <button type="button" x-on:click="btnEdit(product.id)" x-text="product.stock.quantity"></button>
                    </td>
                    <td class="px-6 py-4" x-text="product.size"></td>
                    <td class="px-6 py-4" x-text="product.price"></td>
                    <td class="px-6 py-4 text-right hover:underline">
                        <button x-on:click="btnDelete(product.id)" class="cursor-pointer font-medium text-red-400 hover:underline">delete</button>
                    </td>
                </tr>
            </template>
        </template>
    </tbody>
</table>
