<template>
    <div class="min-h-screen bg-[#F8FAFC]">
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-3xl mx-auto flex justify-between items-center px-6 py-4">
                <div class="flex items-center space-x-3">
                    <Link href="/pharmacy/sales" class="text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </Link>
                    <h1 class="text-xl font-semibold text-slate-900">Invoice {{ sale.invoice_number }}</h1>
                </div>
                <button @click="printInvoice" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium">Print</button>
            </div>
        </header>

        <main class="max-w-3xl mx-auto px-6 py-8">
            <div id="invoice" class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-indigo-600 flex justify-between items-center">
                    <div>
                        <p class="text-white font-bold">MediPrescribe</p>
                        <p class="text-indigo-200 text-xs">Invoice</p>
                    </div>
                    <p class="text-white font-semibold">{{ sale.invoice_number }}</p>
                </div>

                <div class="p-6">
                    <div class="flex justify-between mb-6">
                        <div>
                            <p class="text-xs text-slate-500">Patient</p>
                            <p class="text-sm font-semibold text-slate-900">{{ sale.prescription?.patient?.name || 'Walk-in Customer' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">Date</p>
                            <p class="text-sm text-slate-900">{{ new Date(sale.created_at).toLocaleDateString() }}</p>
                        </div>
                    </div>

                    <table class="w-full mb-6">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-2 text-xs text-slate-500">Medicine</th>
                                <th class="text-right py-2 text-xs text-slate-500">Qty</th>
                                <th class="text-right py-2 text-xs text-slate-500">Price</th>
                                <th class="text-right py-2 text-xs text-slate-500">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in sale.items" :key="item.id" class="border-b border-slate-100">
                                <td class="py-2 text-sm text-slate-900">{{ item.medicine?.name }}</td>
                                <td class="py-2 text-sm text-slate-900 text-right">{{ item.quantity }}</td>
                                <td class="py-2 text-sm text-slate-900 text-right">৳{{ item.unit_price }}</td>
                                <td class="py-2 text-sm font-semibold text-slate-900 text-right">৳{{ item.total_price }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="text-slate-900">৳{{ sale.subtotal }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tax</span>
                            <span class="text-slate-900">৳{{ sale.tax }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-2 flex justify-between">
                            <span class="font-semibold text-slate-900">Grand Total</span>
                            <span class="font-bold text-indigo-600">৳{{ sale.grand_total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    sale: Object,
});

const printInvoice = () => {
    window.print();
};
</script>

<style scoped>
@media print {
    body * { visibility: hidden; }
    #invoice, #invoice * { visibility: visible; }
    #invoice { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>