<template>
    <AppShell title="Create sale" subtitle="Create invoice and deduct stock">
        <template #sidebar><PharmacyNav active="sales" /></template>
        <template #topbar><div class="flex flex-1 items-center justify-between"><Link href="/pharmacy/sales" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600"><ArrowLeftIcon class="h-4 w-4" /> Back to sales</Link><div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">P</div></div></template>
        <form class="pb-28" @submit.prevent="submit">
            <div class="mb-8"><p class="mb-2 text-sm font-medium text-indigo-600">Billing workspace</p><h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Create sale</h1><p class="mt-1 text-sm text-slate-500">Review the order, confirm pricing, and complete the invoice.</p></div>
            <section v-if="prescription" class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Prescription</p><p class="mt-1 text-lg font-bold text-indigo-600">{{ prescription.prescription_number }}</p><p class="mt-1 text-sm text-slate-500">Patient: <span class="font-semibold text-slate-900">{{ prescription.patient?.name }}</span></p></div><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Verified</span></div></section>
            <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><div class="mb-5 flex items-center justify-between"><div><h2 class="text-base font-bold text-slate-900">Sale items</h2><p class="mt-1 text-xs text-slate-500">Confirm quantities and selling prices.</p></div><button type="button" class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100" @click="addItem">+ Add item</button></div><div v-for="(item, index) in form.items" :key="index" class="mb-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4 last:mb-0"><div class="mb-3 flex items-center justify-between"><span class="text-xs font-bold text-indigo-600">ITEM {{ String(index + 1).padStart(2, '0') }}</span><button v-if="form.items.length > 1" type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-700" @click="removeItem(index)">Remove</button></div><div class="grid gap-3 md:grid-cols-[1fr_150px_170px]"><label class="field-label">Medicine<select v-model="item.medicine_id" class="field-input" @change="updatePrice(index)"><option value="">Select medicine</option><option v-for="inv in inventory" :key="inv.medicine_id" :value="inv.medicine_id">{{ inv.medicine?.name }} (Stock: {{ inv.stock_quantity }})</option></select></label><label class="field-label">Quantity<input v-model.number="item.quantity" type="number" min="1" class="field-input" /></label><label class="field-label">Unit price<input v-model.number="item.unit_price" type="number" min="0" step="0.01" class="field-input" /></label></div><div class="mt-3 text-right text-sm font-bold text-indigo-600">৳{{ (item.quantity * item.unit_price).toFixed(2) }}</div></div><p v-if="!form.items.length" class="py-8 text-center text-sm text-slate-400">No items added.</p></section>
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><div class="flex flex-col gap-6 sm:flex-row sm:justify-between"><div><h2 class="text-base font-bold text-slate-900">Totals</h2><p class="mt-1 text-xs text-slate-500">Tax is calculated at 5%.</p></div><div class="w-full space-y-3 text-sm sm:max-w-sm"><div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span class="font-semibold text-slate-900">৳{{ subtotal.toFixed(2) }}</span></div><div class="flex justify-between"><span class="text-slate-500">Tax (5%)</span><span class="font-semibold text-slate-900">৳{{ tax.toFixed(2) }}</span></div><div class="flex items-center justify-between"><span class="text-slate-500">Discount</span><input v-model.number="form.discount" type="number" min="0" step="0.01" class="w-28 rounded-lg border border-slate-300 px-2 py-1.5 text-right text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" /></div><div class="flex justify-between border-t border-slate-200 pt-3"><span class="text-base font-bold text-slate-900">Grand total</span><span class="text-xl font-bold text-indigo-600">৳{{ grandTotal.toFixed(2) }}</span></div></div></div></section>
        </form>
        <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur lg:pl-60"><div class="mx-auto flex max-w-5xl items-center justify-between gap-3 sm:px-4 lg:px-8"><div class="hidden sm:block"><p class="text-xs text-slate-500">{{ form.items.length }} items</p><p class="text-sm font-bold text-slate-900">৳{{ grandTotal.toFixed(2) }}</p></div><div class="ml-auto flex gap-2"><Link href="/pharmacy/sales" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</Link><button type="submit" :disabled="form.processing || !form.items.length" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50" @click="submit">{{ form.processing ? 'Processing...' : 'Complete sale' }}</button></div></div></div>
    </AppShell>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../../Layouts/AppShell.vue';
import PharmacyNav from '../../../Components/PharmacyNav.vue';
const props = defineProps({ prescription: Object, prescriptionItems: { type: Array, default: () => [] }, inventory: { type: Array, default: () => [] } });
const initialItems = props.prescriptionItems.length ? props.prescriptionItems.map((item) => ({ medicine_id: item.medicine_id, quantity: item.quantity, unit_price: item.unit_price })) : [{ medicine_id: '', quantity: 1, unit_price: 0 }];
const form = useForm({ prescription_id: props.prescription?.id || null, items: initialItems, discount: 0 });
const subtotal = computed(() => form.items.reduce((sum, item) => sum + ((Number(item.quantity) || 0) * (Number(item.unit_price) || 0)), 0));
const tax = computed(() => subtotal.value * 0.05);
const grandTotal = computed(() => subtotal.value + tax.value - (Number(form.discount) || 0));
const addItem = () => form.items.push({ medicine_id: '', quantity: 1, unit_price: 0 });
const removeItem = (index) => form.items.splice(index, 1);
const updatePrice = (index) => { const inventoryItem = props.inventory.find((item) => item.medicine_id === form.items[index].medicine_id); if (inventoryItem) form.items[index].unit_price = Number(inventoryItem.selling_price) || 0; };
const submit = () => { if (!form.items.length) { window.alert('Please add at least one item.'); return; } form.post('/pharmacy/sales'); };
</script>

<style scoped>
@reference "../../../../css/app.css";
.field-label { @apply block text-xs font-semibold text-slate-600; }
.field-input { @apply mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20; }
</style>
