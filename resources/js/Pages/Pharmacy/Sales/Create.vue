<template>
    <div class="min-h-screen bg-[#F8FAFC]">
        <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
            <div class="max-w-4xl mx-auto flex justify-between items-center px-6 py-4">
                <div class="flex items-center space-x-3">
                    <Link href="/pharmacy/sales" class="text-slate-500 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </Link>
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">New Sale</h1>
                        <p class="text-sm text-slate-500">Create invoice and deduct stock</p>
                    </div>
                </div>
                <form @submit.prevent="logout">
                    <button type="submit" class="px-3 py-1.5 text-xs text-slate-500 border border-slate-300 rounded-md">Logout</button>
                </form>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-6 py-8 pb-24">
            <!-- Prescription Info -->
            <div v-if="prescription" class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-3">Prescription Details</h2>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-semibold text-indigo-600">{{ prescription.prescription_number }}</p>
                        <p class="text-xs text-slate-500">{{ prescription.patient?.name }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>
                </div>
            </div>

            <!-- Sale Items -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-semibold text-slate-900">Items</h2>
                    <button @click="addItem" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-medium">+ Add Item</button>
                </div>

                <div v-for="(item, index) in form.items" :key="index" class="border border-slate-200 rounded-lg p-4 mb-3 bg-slate-50">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-semibold text-indigo-600">{{ String(index + 1).padStart(2, '0') }}</span>
                        <button v-if="form.items.length > 1" @click="removeItem(index)" class="text-red-400 hover:text-red-600 text-xs">✕ Remove</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Medicine</label>
                            <select v-model="item.medicine_id" class="w-full px-2 py-1.5 border border-slate-300 rounded-md text-sm bg-white" @change="updatePrice(index)">
                                <option value="">Select Medicine</option>
                                <option v-for="inv in inventory" :key="inv.medicine_id" :value="inv.medicine_id">
                                    {{ inv.medicine?.name }} (Stock: {{ inv.stock_quantity }})
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Quantity</label>
                            <input v-model.number="item.quantity" type="number" min="1" class="w-full px-2 py-1.5 border border-slate-300 rounded-md text-sm bg-white" />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Unit Price (৳)</label>
                            <input v-model.number="item.unit_price" type="number" min="0" step="0.01" class="w-full px-2 py-1.5 border border-slate-300 rounded-md text-sm bg-white" />
                        </div>
                    </div>
                    <!-- Item Total -->
                    <div class="mt-2 text-right">
                        <span class="text-xs text-slate-500">Item Total: </span>
                        <span class="text-sm font-semibold text-indigo-600">৳{{ (item.quantity * item.unit_price).toFixed(2) }}</span>
                    </div>
                </div>

                <p v-if="form.items.length === 0" class="text-sm text-slate-400 text-center py-4">No items added.</p>
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">Summary</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-medium text-slate-900">৳{{ subtotal.toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tax (5%)</span>
                        <span class="font-medium text-slate-900">৳{{ tax.toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Discount</span>
                        <input v-model.number="form.discount" type="number" min="0" step="0.01" class="w-24 px-2 py-1 border border-slate-300 rounded-md text-sm text-right" />
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between">
                        <span class="font-semibold text-slate-900">Grand Total</span>
                        <span class="font-bold text-indigo-600 text-lg">৳{{ grandTotal.toFixed(2) }}</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Actions -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 py-4 px-6 z-10">
            <div class="max-w-4xl mx-auto flex justify-between items-center">
                <div>
                    <p class="text-xs text-slate-500">Total Items: {{ form.items.length }}</p>
                    <p class="text-sm font-semibold text-slate-900">Grand Total: ৳{{ grandTotal.toFixed(2) }}</p>
                </div>
                <div class="flex space-x-3">
                    <Link href="/pharmacy/sales" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700">Cancel</Link>
                    <button @click="submit" :disabled="form.processing || form.items.length === 0" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">
                        {{ form.processing ? 'Processing...' : 'Complete Sale' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    prescription: Object,
    prescriptionItems: { type: Array, default: () => [] },
    inventory: { type: Array, default: () => [] },
});

// Pre-filled items from prescription if available
const initialItems = props.prescriptionItems && props.prescriptionItems.length > 0
    ? props.prescriptionItems.map(item => ({
        medicine_id: item.medicine_id,
        quantity: item.quantity,
        unit_price: item.unit_price,
    }))
    : [{ medicine_id: '', quantity: 1, unit_price: 0 }];

const form = useForm({
    prescription_id: props.prescription?.id || null,
    items: initialItems,
    discount: 0,
});

const subtotal = computed(() => {
    return form.items.reduce((sum, item) => {
        const qty = Number(item.quantity) || 0;
        const price = Number(item.unit_price) || 0;
        return sum + (qty * price);
    }, 0);
});

const tax = computed(() => subtotal.value * 0.05);

const grandTotal = computed(() => subtotal.value + tax.value - (Number(form.discount) || 0));

const addItem = () => {
    form.items.push({ medicine_id: '', quantity: 1, unit_price: 0 });
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};

const updatePrice = (index) => {
    const invItem = props.inventory.find(i => i.medicine_id === form.items[index].medicine_id);
    if (invItem) {
        form.items[index].unit_price = parseFloat(invItem.selling_price) || 0;
    }
};

const submit = () => {
    if (form.items.length === 0) {
        alert('Please add at least one item.');
        return;
    }
    form.post('/pharmacy/sales');
};

const logout = () => {
    router.post('/logout');
};
</script>