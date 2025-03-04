<script setup>
defineProps({
    totals: {
        required: true,
        type: Object
    }
})
</script>

<template>
    <div class="mt-4 overflow-x-auto">
        <table id="totals-table" class="min-w-full divide-y divide-gray-200">
            <tbody class="bg-white">
            <tr id="subtotal-row"
                class="border-b {{ totals.hasDiscount ? 'line-through' : 'bg-gray-200' }}">
                <td class="px-4 py-2 font-semibold">Subtotal:</td>
                <td class="px-4 py-2 text-right">
                    ${{ totals.subtotal }}
                </td>
            </tr>
            <tr v-if="totals.hasDiscount" id="discount-row" class="border-t bg-amber-200">
                <td class="px-4 py-2 font-bold">
                    Subtotal:
                    <p class="text-sm text-gray-400">(with Saturday Discount)</p>
                </td>
                <td class="px-4 py-2 font-bold text-right">
                    ${{ totals.totalBeforeFees }}
                </td>
            </tr>
            <tr id="tax-row" class="border-b text-sm">
                <td class="px-4 py-2 font-semibold">
                    Tax (<span class="fee-rate text-gray-500">2.69%</span>):
                </td>
                <td class="px-4 py-2 text-right">
                    ${{ totals.tax }}
                </td>
            </tr>
            <tr id="store-fee-row" class="border-b text-sm">
                <td class="px-4 py-2 font-semibold">
                    Store Fee (<span
                    class="fee-rate text-gray-500">5%</span>):
                </td>
                <td class="px-4 py-2 text-right">
                    ${{ totals.storeFee }}
                </td>
            </tr>
            <tr id="duties-row" class="border-b text-sm">
                <td class="px-4 py-2 font-semibold">
                    Custom Duties (<span
                    class="fee-rate text-gray-500">3.35%</span>):
                </td>
                <td class="px-4 py-2 text-right">
                    ${{ totals.dutiesFee }}
                </td>
            </tr>
            <tr id="total-row" class="border-t text-lg bg-blue-300">
                <td class="px-4 py-2 font-bold">Total:</td>
                <td class="px-4 py-2 font-bold text-right">
                    ${{ totals.total.toFixed(2) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
