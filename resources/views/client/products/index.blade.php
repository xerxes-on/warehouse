<x-app-layout>
    <div class="py-4">
        <form class="flex items-center max-w-sm mx-auto">
            <label for="simple-search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2"/>
                    </svg>
                </div>
                <input type="text" id="simple-search"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Search for a product..." required/>
                <div id="search-results"
                     class="absolute z-10 w-full bg-gray-700 border border-gray-600 rounded-md shadow-lg hidden">
                    <ul class="py-1 text-sm text-gray-200 divide-y divide-gray-600">
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="products-container">
        @foreach ($products as $product)
            <div class="shadow rounded-lg product-card p-2 bg-gray-800 relative">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="/images/cat.jpg"
                         alt="Random Cat for {{ $product->name }}"
                         class="h-48 w-full object-cover rounded-t-lg">
                </a>
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-200">
                        {{ $product->name }}
                    </h2>
                    <div class="mt-2 flex items-center justify-between  ">
                        <p class="text-lg text-white">${{ number_format($product->price, 2) }}</p>
                        <p class="px-1 py-1 text-white font-bold text-sm bg-amber-700 rounded-2xl text-right">Left: {{ $product->quantity_left }}</p>
                    </div>
                    @if (session('user_role') != 'admin')

                        <div class="mt-3 flex items-center justify-between space-x-2">
                            <div>
                                <button type="button"
                                        class="decrement-button text-lg   text-white px-2 py-1 rounded">-
                                </button>

                                <input type="number"
                                       class="quantity-field w-16 text-center text-white bg-transparent h-5 border-none"
                                       value="1"
                                       min="1"
                                       max="{{$product->quantity_left}}">

                                <button type="button"
                                        class="increment-button text-xl text-white px-2 py-1 rounded">+
                                </button>
                            </div>
                            <button type="button" class="add-to-cart rounded" data-product-id="{{ $product->id }}">
                                <i id="cart-{{$product->id}}" class="fa-solid fa-cart-shopping"
                                   style="color: #ff2e2e;"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6 w-1/2 mx-auto">
        {{ $products->links() }}
    </div>
    <script>
        window.indexProducts = {}
        window.indexProducts.routes = {
            search:'{{ route("products.search") , }}',
            addToCart: '{{ route("cart.add") }}',
        }
    </script>
</x-app-layout>
