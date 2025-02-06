<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-white mb-6">Products</h1>
            @if (session('user_role') === 'admin')
                <a href="{{ route('products.create') }}" class="">
                    <x-primary-button>
                        Create
                    </x-primary-button>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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
            ;
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
