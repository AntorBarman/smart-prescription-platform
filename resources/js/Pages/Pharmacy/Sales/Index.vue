<template>
    <div class="min-h-screen bg-[#F8FAFC] flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-slate-200 fixed inset-y-0 z-20">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">MediPrescribe</p>
                        <p class="text-xs text-slate-500">Pharmacy Panel</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 py-4">
                <Link href="/pharmacy/dashboard" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Dashboard</Link>
                <Link href="/pharmacy/inventory" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Inventory</Link>
                <Link href="/pharmacy/scanner" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">QR Scanner</Link>
                <Link href="/pharmacy/sales" class="flex items-center px-4 py-2.5 text-sm bg-indigo-50 text-indigo-700 font-medium rounded-lg mx-2">Sales</Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Sales History</h1>
                        <p class="text-sm text-slate-500">View all completed sales</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link href="/pharmacy/sales/create" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">+ New Sale</Link>
                        <form @submit.prevent="logout">
                            <button type="submit" class="px-3 py-1.5 text-xs text-slate-500 border border-slate-300 rounded-md">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-6 py-6">
                <!-- Success Message -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">{{ $page.props.flash.success }}</p>
                </div>

                <!-- Sales Table -->
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="sale in sales.data" :key="sale.id" class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm font-semibold text-indigo-600">{{ sale.invoice_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900">{{ sale.prescription?.patient?.name || '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ new Date(sale.created_at).toLocaleDateString() }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-slate-900">৳{{ sale.grand_total }}</td>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="`/pharmacy/sales/${sale.id}`" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</Link>
                                </td>
                            </tr>
                            <tr v-if="!sales.data || sales.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">No sales yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({
    sales: Object,
});

const logout = () => {
    router.post('/logout');
};
</script>