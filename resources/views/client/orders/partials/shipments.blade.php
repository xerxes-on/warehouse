<div class="grid grid-cols-1 w-fit space-y-2">
    @foreach ($order->shipments as $shipment)
        <div class="border border-gray-300 shadow rounded-2xl relative">
            <a href="{{ route('shipment.show', $shipment->id) }}">
                <div class="py-4 px-2 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-200">Shipment id: {{ $shipment->id }}</h2>
                    <span
                        class="bg-amber-700 text-white rounded-2xl px-2 mx-2 text-sm ">{{$shipment->status->toString()}}</span>
                </div>
            </a>
        </div>
    @endforeach
</div>
