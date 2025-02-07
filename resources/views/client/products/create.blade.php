<x-app-layout>
    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-white mb-6">Create New Product</h1>
        <form method="POST" action="{{ route('products.store') }}"
              class="border border-gray-400  rounded-lg p-6 space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Product Name</label>
                <input type="text" name="name" id="name"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>

            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-300">Price</label>
                <input type="number" name="price" id="price" step="0.01"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                <x-input-error :messages="$errors->get('price')" class="mt-2"/>

            </div>
            <div class="text-right">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
