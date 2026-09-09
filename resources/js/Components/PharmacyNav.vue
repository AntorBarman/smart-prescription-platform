<template>
    <div class="flex h-full flex-col">
        <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white"><BuildingStorefrontIcon class="h-5 w-5" /></div><div><p class="text-sm font-bold text-slate-900">MediPrescribe</p><p class="text-[11px] text-slate-500">Pharmacy workspace</p></div></div>
        <nav class="flex-1 space-y-1 px-3 py-5"><p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Operations</p><Link v-for="item in items" :key="item.label" :href="item.href" :class="['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition', active === item.key ? 'bg-indigo-600 font-semibold text-white shadow-sm' : 'font-medium text-slate-600 hover:bg-indigo-50 hover:text-indigo-700']"><component :is="item.icon" class="h-5 w-5" />{{ item.label }}</Link></nav>
        <div class="border-t border-slate-100 p-4"><button type="button" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" @click="logout"><ArrowRightOnRectangleIcon class="h-5 w-5" />Sign out</button></div>
    </div>
</template>
<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ArrowRightOnRectangleIcon, BeakerIcon, BuildingStorefrontIcon, ClipboardDocumentListIcon, CurrencyDollarIcon, QrCodeIcon } from '@heroicons/vue/24/outline';
defineProps({ active: { type: String, default: '' } });
const items = [{ key: 'dashboard', label: 'Overview', href: '/pharmacy/dashboard', icon: BuildingStorefrontIcon }, { key: 'inventory', label: 'Inventory', href: '/pharmacy/inventory', icon: BeakerIcon }, { key: 'scanner', label: 'QR scanner', href: '/pharmacy/scanner', icon: QrCodeIcon }, { key: 'sales', label: 'Sales', href: '/pharmacy/sales', icon: CurrencyDollarIcon }, { key: 'prescriptions', label: 'Prescriptions', href: '/prescriptions', icon: ClipboardDocumentListIcon }];
const logout = () => router.post('/logout');
</script>
