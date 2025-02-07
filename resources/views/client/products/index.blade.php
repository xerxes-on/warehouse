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
                    <div class="mt-2 text-xl font-bold text-gray-200">
                        ${{ number_format($product->price, 2) }}
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
                                       min="1">

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

    <div class="mt-6">
        {{ $products->links() }}
    </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#simple-search').on('keyup', function () {
                let needle = $(this).val();

                if (needle.length < 2) {
                    $('#search-results').addClass('hidden');
                    return;
                }

                $.ajax({
                    url: '{{ route("products.search") , }}',
                    type: 'GET',
                    data: {needle: needle},
                    success: function (response) {
                        let resultsHtml = '';
                        if (response.data.length > 0) {
                            resultsHtml += '<ul class="py-1 text-sm text-gray-200 divide-y divide-gray-600">';
                            $.each(response.data, function (index, product) {
                                resultsHtml += `<li><a href="/products/${product.id}" class="block px-4 py-2 hover:bg-gray-600">${product.name}</a></li>`;
                            });
                            resultsHtml += '</ul>';
                            $('#search-results').html(resultsHtml);
                            $('#search-results').removeClass('hidden');
                        } else {
                            $('#search-results').addClass('hidden');
                        }
                    },
                    error: function (error) {
                        console.error('Error:', error);
                        $('#search-results').addClass('hidden');
                    }
                });
            });
        });

        $(document).on('click', '.increment-button', function () {
            let input = $(this).siblings('.quantity-field');
            let currentVal = parseInt(input.val());
            input.val(currentVal + 1);
        });

        $(document).on('click', '.decrement-button', function () {
            let input = $(this).siblings('.quantity-field');
            let currentVal = parseInt(input.val());
            if (currentVal > 1) {
                input.val(currentVal - 1);
            }
        });
        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();
            let button = $(this);
            let productId = button.data('product-id');

            let quantityInput = button.closest('.product-card').find('.quantity-field');
            let quantity = quantityInput.val();

            $.ajax({
                url: '{{ route("cart.add") }}',
                type: 'POST',
                data: {
                    id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#total-products').text(response.data)
                    $('#cart-' + productId).toggleClass('animate-flip');
                },
                error: function (e) {
                    alert("Error adding product to cart: " + (e.responseJSON.message || "Unknown error"));
                }
            });
        });
    </script>
</x-app-layout>
