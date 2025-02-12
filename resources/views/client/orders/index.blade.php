<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-white mb-6">Orders</h1>
        </div>
        <div class="grid grid-cols-1 mt-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($orders as $order)
                <div class="border border-gray-300 shadow rounded-lg p-1">
                    @if($order->shipment_id)
                        <p class="text-amber-500 font-bold">{{$order->shipment->status->toString()}}</p>
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}">
                        <div class="p-4 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-200">Order id: {{ $order->id }}</h2>
                            <p class="text-black font-bold bg-amber-500 rounded-2xl py-2 px-3">{{$order->status->toString()}}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
