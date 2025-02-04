<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-white mb-6">Product</h1>
        <div class="flex justify-between items-center">
            <div class="rounded-lg">
                <img src="/images/cat.jpg"
                     alt="Random Cat for {{ $product->name }}"
                     class="h-48 w-full object-cover rounded-t-lg">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-200">{{ $product->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ Str::limit($product->description, 100) }}</p>
                    <div class="mt-4 flex justify-between items-center">
                            <span
                                class="text-xl font-bold text-gray-200">${{ number_format($product->price, 2) }}</span>
                        @if (session('user_role') === 'admin')
                            <form action="{{route('products.destroy', $product->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure to delete?')"
                                        class="text-indigo-600 hover:underline">
                                    <i class="fa-solid fa-trash" style="color: #ff2e2e;"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('products.show', $product->id) }}"
                               class="text-indigo-600 hover:underline">
                                <i class="fa-solid fa-cart-shopping" style="color: #ff2e2e;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @if(session('user_role') == 'admin')
                <div class="px-4 sm:px-6 lg:px-8 py-6 w-1/2">
                    <h1 class="text-2xl font-bold text-white mb-6">Edit</h1>
                    <form method="POST" action="{{ route('products.update', $product->id) }}"
                          class="border border-gray-400  rounded-lg p-6 space-y-6">
                        @method('PUT')
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">Product Name</label>
                            <input type="text" name="name" id="name"
                                   value="{{ $product->name }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-300">Price</label>
                            <input type="number" name="price" id="price" step="0.01"
                                   value="{{$product->price}}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
