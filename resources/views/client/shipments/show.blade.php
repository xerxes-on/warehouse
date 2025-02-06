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
                        <strong>Sent:</strong> {{ $shipment->date_shipped ? $shipment->date_shipped->format('Y-m-d H:i') : 'Not Shipped Yet' }}
                    </p>
                </div>
            </div>
        </div>
        <h1 class="text-center text-gray-200 text-xl font-bold mb-4">Orders in the shipment </h1>
        <div class="flex px-2 justify-end space-x-2 items-end">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($shipment->orders as $order)
                <div
                    class="border border-gray-300 shadow rounded-lg p-4 bg-gray-700">
                    <a href="{{ route('orders.show', $order->id) }}">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-lg font-semibold text-gray-200">Order #{{ $order->id }}</h2>
                            <p class="bg-amber-700 text-white font-bold rounded-2xl py-1 px-2 text-lg">{{$order->status->toString()}}</p>
                        </div>
                        <div>
                            @foreach ($order->orderItems as $product)
                                <div class="mb-2 border-b flex border-gray-600 pb-1">
                                    <p class="inline text-gray-300">{{ $product->price ?? 'N/A' }}
                                        x {{ $product->quantity }}</p>
                                    <p class="inline text-gray-300"> = ${{$product->total_price}}</p>
                                </div>
                            @endforeach
                            <h1 class="text-gray-300 text-end">${{$order->total_price}}</h1>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
