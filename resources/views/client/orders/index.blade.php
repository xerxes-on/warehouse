<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-white mb-6">Orders</h1>
            @if(session('user_role') == 'admin' && $canCreate)
                <div class="w-1/2 flex justify-between">
                    <div class="w-full flex items-center">
                        <label class="text-gray-200">Warehouse</label>
                        <select id="warehouse" name="warehouse"
                                class="mx-1 h-10 block w-1/3 rounded-md bg-transparent text-gray-200">
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <label class="text-gray-200 ml-4">Branch</label>
                        <select id="branch" name="branch"
                                class="mx-1 h-10 bg-transparent block w-1/3 rounded-md text-gray-200">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-secondary-button id="create-shipment-btn ">
                        Create Shipment
                    </x-secondary-button>
                </div>
            @endif
        </div>
        <div class="grid grid-cols-1 mt-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($orders as $order)
                <div class="border border-gray-300 shadow rounded-lg p-1">
                    @if(session('user_role') == 'admin' && $canCreate && !$order->shipment_id)
                        <div class="px-4 py-2">
                            <input
                                type="checkbox"
                                class="create-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded"
                                value="{{ $order->id }}"
                            />
                        </div>
                    @endif
                    @if($order->shipment_id)
                        <p class="text-amber-500 font-bold">{{$order->shipment->status->toString()}}</p>
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}">
                        <div class="p-4 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-200">Order id: {{ $order->id }}</h2>
                            <p class="text-blue-800 font-bold bg-blue-300 rounded-2xl py-2 px-3">{{$order->status->toString()}}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
    <script>
        $(document).ready(function () {
            const $shipmentBtn = $('#create-shipment-btn');
            $shipmentBtn.on('click', function () {
                const checkedItems = $('.create-checkbox:checked')
                    .map(function () {
                        return $(this).val();
                    })
                    .get();
                const warehouseId = $('#warehouse').val();
                const branchId = $('#branch').val();
                if (!checkedItems.length) {
                    alert('No orders');
                    return
                }
                $.ajax({
                    url: "{{route('shipment.store')}}",
                    type: 'POST',
                    data: {
                        _token: '{{csrf_token()}}',
                        ids: checkedItems,
                        warehouse_id: warehouseId,
                        branch_id: branchId
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Success adding items.');
                        } else {
                            alert('Error adding items.');
                        }
                    },
                    error: function () {
                        alert('Error adding items.');
                    }
                });
            });
        });
    </script>
</x-app-layout>
