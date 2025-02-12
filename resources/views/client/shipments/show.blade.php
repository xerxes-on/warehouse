@php use App\Enums\ShipmentStatus; @endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Shipment #{{$shipment->id}}</h1>
                <p class="text-gray-400 text-sm">Created: {{ $shipment->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <h1 class="text-center text-amber-500 text-xl">Shipment Status:
                <span class="text-white">{{$shipment->status->tostring()}}</span>
            </h1>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-white mb-2">Shipment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-300"><strong>To:</strong> {{ $shipment->branch->name ?? 'N/A' }}
                        , {{ $shipment->branch->location ?? 'N/A' }}</p>
                    <p class="text-gray-300"><strong>From:</strong> {{ $shipment->warehouse->name ?? 'N/A' }}
                        , {{ $shipment->warehouse->location ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-300">
                        <strong>Sent:</strong> {{ $shipment->date_shipped ?: 'Not Shipped Yet' }}
                    </p>
                </div>
            </div>
        </div>
        <h1 class="text-center text-gray-200 text-xl font-bold mb-4">Order of the shipment </h1>
        <div class="flex px-2 justify-end space-x-2 items-end my-2">
            @if($shipment->status == ShipmentStatus::DELIVERING)
                <form method="post" action="{{route('shipment.update', $shipment->id)}}">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="status" value="{{ShipmentStatus::DELIVERED}}">
                    <x-secondary-button type="submit">
                        Mark as shipped
                    </x-secondary-button>
                </form>
            @endif
            @if($shipment->status !== ShipmentStatus::RETURNED)
                <form method="post" action="{{route('shipment.update', $shipment->id)}}">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="status" value="{{ShipmentStatus::RETURNED}}">
                    <x-secondary-button type="submit">
                        Mark as returned
                    </x-secondary-button>
                </form>
            @endif
        </div>
        <div class="flex justify-center items-center w-full">
            <div
                class="border border-gray-300 w-1/2 shadow rounded-lg p-4 bg-gray-700">
                <a href="{{ route('orders.show', $shipment->order->id) }}">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-lg font-semibold text-gray-200">Order #{{ $shipment->order->id  }}</h2>
                        <p class="bg-amber-700 text-white font-bold rounded-2xl py-1 px-2 text-lg">{{$shipment->order->status->toString()}}</p>
                    </div>
                    <div>
                        <table class="min-w-full divide-y divide-gray-600">
                            <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-white">Product</th>
                                <th class="px-4 py-2 text-left text-white">Price</th>
                                <th class="px-4 py-2 text-left text-white">Quantity</th>
                                <th class="px-4 py-2 text-left text-white">Total</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                            @foreach ($shipment->shipmentItems as $item)
                                <tr>
                                    <td class="px-4 py-2 text-white">{{ $item->orderItem->product->name }}</td>
                                    <td class="px-4 py-2 text-gray-300">
                                        ${{ $item->orderItem->product->price ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-300">
                                        {{ $item->orderItem->quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-300">
                                        ${{ $item->orderItem->total_price }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
