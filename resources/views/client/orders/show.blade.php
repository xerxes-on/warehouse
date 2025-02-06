<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl text-center font-bold text-white mb-6">
            Order #{{ $order->id }}
        </h1>
        <div class="flex justify-center">
            @if(count($order->orderItems)>=1)
                <div class="bg-white rounded-2xl p-6 w-full max-w-2xl">
                    <div class="mb-4">
                        <span class="font-semibold bg-green-300">Status: {{$order->status->toString()}}</span>
                    </div>
                    <div class="overflow-x-auto mb-4">
                        <table class="min-w-full divide-y divide-gray-200" id="order-items-table">
                            <thead class="bg-gray-100">
                            <tr>
                                @if($order->status == \App\Enums\OrderStatus::CART)
                                    <th class="px-4 py-2 text-left text-gray-700 font-bold">Select</th>
                                @endif
                                <th class="px-4 py-2 text-left text-gray-700 font-bold">Product</th>
                                <th class="px-4 py-2 text-left text-gray-700 font-bold">Price</th>
                                <th class="px-4 py-2 text-left text-gray-700 font-bold">Amount</th>
                                <th class="px-4 py-2 text-left text-gray-700 font-bold">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr data-item-id="{{ $item->id }}"
                                    data-original-qty="{{ $item->quantity }}"
                                    data-product-id="{{$item->product_id}}">
                                    @if($order->status == \App\Enums\OrderStatus::CART)
                                        <td class="px-4 py-2">
                                            <input
                                                type="checkbox"
                                                class="delete-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded"
                                                value="{{ $item->product_id }}"
                                            />
                                        </td>
                                    @endif
                                    <td class="px-4 py-2">
                                        {{ $item->product_name }}
                                    </td>
                                    <td class="px-4 py-2">
                                        ${{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="amount-label">
                                                {{ $item->quantity }}
                                            </span>
                                            <input
                                                type="number"
                                                min="1"
                                                class="amount-input border rounded w-16 p-1 hidden"
                                                value="{{ $item->quantity }}"
                                            />
                                            @if($order->status == \App\Enums\OrderStatus::CART)
                                                <i class="fas fa-pen text-blue-500 cursor-pointer edit-quantity"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        ${{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($order->status == \App\Enums\OrderStatus::CART)
                            <div class="flex items-center justify-end space-x-4 mb-4">
                                <button
                                    id="delete-selected-btn"
                                    class="px-4 py-2 bg-red-500 text-white rounded hidden disabled:opacity-50"
                                    disabled
                                >
                                    Delete Selected
                                </button>
                                <button
                                    id="save-changes-btn"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hidden disabled:opacity-50"
                                    disabled
                                >
                                    Save Changes
                                </button>
                            </div>
                        @endif
                    </div>
                    @include('client.orders.partials.totals')
                    @if($order->status == \App\Enums\OrderStatus::CART)
                        <div class="flex items-center justify-end p-5">
                            <form method="POST" action="{{route('orders.update', $order->id)}}">
                                @csrf
                                @method('PUT')
                                <input type="number" name="status" value="{{\App\Enums\OrderStatus::ORDERED}}"
                                       hidden="">
                                <x-secondary-button type="submit">
                                    Order
                                </x-secondary-button>
                            </form>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex items-center  justify-center w-full h-fit">
                    <img src="/images/empty.gif" alt="duck gif" class="rounded-2xl">
                </div>
            @endif
        </div>
    </div>
    @push('scripts')
        <script>
            window.showOrder = {};
            window.showOrder.vars = {
                orderId: {{ $order->id }},
                csrf: '{{ csrf_token() }}',
            };
            window.showOrder.routes = {
                removeItems: "{{route("cart.remove-items")}}",
                updateItems:'{{ route('cart.update-items') }}',
                refreshItems:'{{ route("orders.refresh-details", $order->id) }}'
            }
        </script>
    @endpush
</x-app-layout>
