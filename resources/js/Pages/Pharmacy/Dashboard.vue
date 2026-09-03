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
                <Link href="/pharmacy/dashboard" class="flex items-center px-4 py-2.5 text-sm bg-indigo-50 text-indigo-700 font-medium rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </Link>
                <Link href="/pharmacy/inventory" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Inventory
                </Link>
                <Link href="/pharmacy/scanner" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    QR Scanner
                </Link>
                <Link href="/pharmacy/sales" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Sales
                </Link>
                <Link href="/prescriptions" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Prescriptions
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Pharmacy Dashboard</h1>
                        <p class="text-sm text-slate-500">Manage your pharmacy operations</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2 border-r border-slate-200 pr-4">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-medium">P</div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-slate-500">Pharmacist</p>
                            </div>
                        </div>
                        <form @submit.prevent="logout">
                            <button type="submit" class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-700 border border-slate-300 rounded-md">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-6 py-6">
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.totalMedicines }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Medicines in Stock</p>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-red-600">{{ stats.lowStock }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Low Stock Items</p>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.prescriptionsProcessed }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Prescriptions Processed</p>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">৳{{ stats.totalSales }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Total Sales</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <Link href="/pharmacy/inventory" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Manage Inventory</p>
                        <p class="text-xs text-slate-500 mt-1">View and manage your medicine stock</p>
                    </Link>
                    <Link href="/pharmacy/scanner" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-100 transition">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">QR Scanner</p>
                        <p class="text-xs text-slate-500 mt-1">Scan and verify prescriptions</p>
                    </Link>
                    <Link href="/pharmacy/sales" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-100 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Sales & Billing</p>
                        <p class="text-xs text-slate-500 mt-1">Create invoices and track sales</p>
                    </Link>
                    <Link href="/prescriptions" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-100 transition">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Prescriptions</p>
                        <p class="text-xs text-slate-500 mt-1">View all prescriptions</p>
                    </Link>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalMedicines: 0,
            lowStock: 0,
            prescriptionsProcessed: 0,
            totalSales: 0,
        }),
    },
});

const logout = () => {
    router.post('/logout');
};
</script>