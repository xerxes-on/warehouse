<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-white mb-6">Orders</h1>
            @if(session('user_role') == 'admin')
                <div class="px-4 py-2">
                    <x-secondary-button>
                        Create Shipment
                    </x-secondary-button>
                </div>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($orders as $order)
                <div class="border border-gray-300 shadow rounded-lg p-1">
                    @if(session('user_role') == 'admin')
                        <div class="px-4 py-2">
                            <input
                                type="checkbox"
                                class="delete-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded"
                                value="{{ $order->id }}"
                            />
                        </div>
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
</x-app-layout>
