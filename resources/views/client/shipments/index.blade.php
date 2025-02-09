<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-white mb-6">Deliveries</h1>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($shipments as $shipment)
                <div class="border border-gray-300 shadow rounded-2xl relative">
                    <a href="{{ route('shipment.show', $shipment->id) }}">
                        <div class="py-4 px-2 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-200">Shipment id: {{ $shipment->id }}</h2>
                            <p class="text-blue-800 font-bold bg-blue-300 rounded-2xl py-2 px-3">{{$shipment->status->toString()}}</p>
                            <span
                                class="bg-amber-700 text-white absolute rounded-2xl px-1 text-sm top-0">#orderId: {{$shipment->order->id}}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $shipments->links() }}
        </div>
    </div>
</x-app-layout>
