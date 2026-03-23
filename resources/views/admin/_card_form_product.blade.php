 <form x-on:submit.prevent="sendDataProduct">
    <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
        <div class="col-span-2 sm:col-span-1">
            <label for="name" class="block mb-2.5 text-sm font-medium text-heading">nama</label>
            <input type="text" x-model="product.name" id="name" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="Type product name">
            <template x-if="errors.name">
                <p class="mt-2.5 text-sm text-red-600 font-semibold" x-text="errors.name"><span class="font-medium"></span></p>
            </template>
        </div>
         <div class="col-span-2 sm:col-span-1">
            <label for="name" class="block mb-2.5 text-sm font-medium text-heading">jumlah</label>
            <select id="category" x-model="product.quantity" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                <option value="">pilih jumlah</option>
                <template x-for="index in 10" :key="index">
                    <option :value="index" x-text="index"></option>
                </template>
            </select>
            <template x-if="errors.quantity">
                <p class="mt-2.5 text-sm text-red-600 font-semibold" x-text="errors.quantity"><span class="font-medium"></span></p>
            </template>
        </div>
        <div class="col-span-2 sm:col-span-1">
            <label for="price" class="block mb-2.5 text-sm font-medium text-heading">harga</label>
            <input type="number" x-model="product.price" id="price" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="$2999">
            <template x-if="errors.price">
                <p class="mt-2.5 text-sm text-red-600 font-semibold" x-text="errors.price"><span class="font-medium"></span></p>
            </template>
        </div>
        <div class="col-span-2 sm:col-span-1">
            <label for="category" class="block mb-2.5 text-sm font-medium text-heading">ukuran</label>
            <select id="category" x-model="product.size" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                <option value="">pilih ukuran</option>
                <template x-for="size in listSize" :key="size">
                    <option :value="size" x-text="size"></option>
                </template>
            </select>
            <template x-if="errors.size">
                <p class="mt-2.5 text-sm text-red-600 font-semibold" x-text="errors.size"><span class="font-medium"></span></p>
            </template>
        </div>
        <div class="col-span-2">
            <label for="description" class="block mb-2.5 text-sm font-medium text-heading">keterangan</label>
            <textarea id="description" x-model="product.description" rows="4" class="block bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body" placeholder="Write product description here"></textarea>
            <template x-if="errors.description">
                <p class="mt-2.5 text-sm text-red-600 font-semibold" x-text="errors.description"><span class="font-medium"></span></p>
            </template>
        </div>
    </div>
    <div class="flex items-center space-x-4 pt-4 md:pt-6">
        <button type="submit" class="inline-flex items-center  text-white bg-blue-400 hover:bg-blue-600 rounded-lg box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            simpan
        </button>
        <button x-on:click="btnCancelCreate" type="button" class="text-body bg-gray-400 rounded-lg box-border border border-default-medium hover:bg-gray-600 hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Cancel</button>
    </div>
</form>
