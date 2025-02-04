<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl text-center font-bold text-white mb-6">
            {{--            @dd($order)--}}
            Order #{{ $order->id }}
        </h1>
        <div class="flex justify-center">
            <div class="bg-white rounded-2xl  p-6 w-full max-w-2xl">
                <div class="mb-4">
                    <span class="font-semibold bg-green-300">Status: {{$order->status->toString()}}</span>
                </div>
                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Product</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Price</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Amount</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->product_name }}</td>
                                <td class="px-4 py-2">
                                    ${{ number_format($item->price, 2) }}
                                </td>
                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                <td class="px-4 py-2">
                                    ${{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white">
                        <tr class="border-b {{$details['discount']>0?'line-through':'bg-gray-200'}}">
                            <td class="px-4 py-2 font-semibold">Subtotal:</td>
                            <td class="px-4 py-2 text-right ">
                                ${{ number_format($details['subtotal'], 2) }}
                            </td>
                        @if($details['discount']>0)
                            <tr class="border-t bg-amber-200">
                                <td class="px-4 py-2 font-bold">Subtotal:
                                    <p class="text-sm text-gray-400">(with Saturday Discount)<p>
                                </td>
                                <td class="px-4 py-2 font-bold text-right">
                                    ${{ number_format($details['totalBeforeFees'], 2) }}
                                </td>
                            </tr>
                        @endif
                        <tr class="border-b text-sm">
                            <td class="px-4 py-2 font-semibold">
                                Tax (<span class="text-gray-500">{{ \App\Enums\Fees::$tax }}%</span>):
                            </td>
                            <td class="px-4 py-2 text-right">
                                ${{ number_format($details['tax'], 2) }}
                            </td>
                        </tr>
                        <tr class="border-b text-sm">
                            <td class="px-4 py-2 font-semibold">
                                Store Fee (<span class="text-gray-500">{{ \App\Enums\Fees::$store }}%</span>):
                            </td>
                            <td class="px-4 py-2 text-right">
                                ${{ number_format($details['storeFee'], 2) }}
                            </td>
                        </tr>
                        <tr class="border-b text-sm">
                            <td class="px-4 py-2 font-semibold">
                                Custom Duties (<span class="text-gray-500">{{ \App\Enums\Fees::$duties }}%</span>):
                            </td>
                            <td class="px-4 py-2 text-right">
                                ${{ number_format($details['dutiesFee'], 2) }}
                            </td>
                        </tr>
                        <tr class="border-t text-lg bg-blue-300">
                            <td class="px-4 py-2 font-bold">Total:</td>
                            <td class="px-4 py-2 font-bold text-right">
                                ${{ number_format($details['total'], 2) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
