<template>
    <AppShell title="Pharmacy overview" subtitle="Monitor stock and prescription fulfilment">
        <template #sidebar>
            <PharmacyNav active="dashboard" />
        </template>
        <template #topbar>
            <div class="flex flex-1 items-center justify-between">
                <div class="hidden items-center gap-2 text-sm text-slate-500 sm:flex">Pharmacy <ChevronRightIcon class="h-4 w-4" /><span class="font-semibold text-slate-900">Overview</span></div>
                <div class="flex items-center gap-3"><BellIcon class="h-5 w-5 text-slate-400" /><div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">{{ initials }}</div></div>
            </div>
        </template>
        <section class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="mb-2 text-sm font-medium text-indigo-600">Operations center</p><h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Pharmacy overview</h1><p class="mt-1 text-sm text-slate-500">A clear view of your stock and daily workflow.</p></div><span class="text-sm text-slate-500">{{ today }}</span></section>
        <div class="mb-8 grid grid-cols-2 gap-4 xl:grid-cols-4">
            <div v-for="stat in statCards" :key="stat.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"><div class="mb-5 flex items-center justify-between"><span :class="['flex h-10 w-10 items-center justify-center rounded-lg', stat.bg]"><component :is="stat.icon" :class="['h-5 w-5', stat.color]" /></span><ArrowUpRightIcon class="h-4 w-4 text-slate-300" /></div><p class="text-2xl font-bold text-slate-900">{{ stat.value }}</p><p class="mt-1 text-sm text-slate-500">{{ stat.label }}</p></div>
        </div>
        <div class="grid gap-6 xl:grid-cols-[1fr_340px]">
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><div class="mb-4 flex items-center justify-between"><div><h2 class="text-sm font-bold text-slate-900">Quick actions</h2><p class="mt-1 text-xs text-slate-500">Move through your daily workflow faster.</p></div></div><div class="grid gap-3 sm:grid-cols-2"><Link v-for="action in actions" :key="action.label" :href="action.href" class="group flex items-center gap-3 rounded-lg border border-slate-100 p-3 transition hover:-translate-y-0.5 hover:border-indigo-100 hover:bg-indigo-50"><span :class="['flex h-10 w-10 items-center justify-center rounded-lg', action.bg]"><component :is="action.icon" :class="['h-5 w-5', action.color]" /></span><span class="flex-1"><span class="block text-sm font-semibold text-slate-800">{{ action.label }}</span><span class="block text-xs text-slate-500">{{ action.description }}</span></span><ArrowRightIcon class="h-4 w-4 text-slate-300 group-hover:text-indigo-500" /></Link></div></section>
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><div class="mb-4 flex items-center justify-between"><div><h2 class="text-sm font-bold text-slate-900">Stock attention</h2><p class="mt-1 text-xs text-slate-500">Items that may need action.</p></div><Link href="/pharmacy/inventory" class="text-xs font-semibold text-indigo-600">View inventory</Link></div><div class="rounded-lg bg-amber-50 p-4"><div class="flex items-center gap-3"><ExclamationTriangleIcon class="h-5 w-5 text-amber-600" /><div><p class="text-sm font-semibold text-amber-900">{{ stats.lowStock }} low-stock items</p><p class="text-xs text-amber-700">Review reorder levels today.</p></div></div></div></section>
        </div>
    </AppShell>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon, ArrowUpRightIcon, BeakerIcon, BellIcon, ClipboardDocumentListIcon, ChevronRightIcon, ExclamationTriangleIcon, QrCodeIcon, ShoppingCartIcon, UsersIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';
import PharmacyNav from '../../Components/PharmacyNav.vue';
const props = defineProps({ stats: { type: Object, default: () => ({ totalMedicines: 0, lowStock: 0, prescriptionsProcessed: 0, totalSales: 0 }) } });
const page = usePage();
const initials = computed(() => (page.props.auth.user.name || 'P').split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase());
const today = new Date().toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' });
const statCards = computed(() => [{ label: 'Total stock units', value: props.stats.totalMedicines, icon: BeakerIcon, bg: 'bg-indigo-50', color: 'text-indigo-600' }, { label: 'Low stock items', value: props.stats.lowStock, icon: ExclamationTriangleIcon, bg: 'bg-amber-50', color: 'text-amber-600' }, { label: 'Prescriptions processed', value: props.stats.prescriptionsProcessed, icon: ClipboardDocumentListIcon, bg: 'bg-emerald-50', color: 'text-emerald-600' }, { label: 'Total sales', value: `৳${props.stats.totalSales}`, icon: ShoppingCartIcon, bg: 'bg-violet-50', color: 'text-violet-600' }]);
const actions = [{ label: 'Scan prescription', description: 'Verify a patient QR code', href: '/pharmacy/scanner', icon: QrCodeIcon, bg: 'bg-indigo-50', color: 'text-indigo-600' }, { label: 'Manage inventory', description: 'Review stock levels', href: '/pharmacy/inventory', icon: BeakerIcon, bg: 'bg-emerald-50', color: 'text-emerald-600' }, { label: 'New sale', description: 'Start a pharmacy sale', href: '/pharmacy/sales/create', icon: ShoppingCartIcon, bg: 'bg-amber-50', color: 'text-amber-600' }, { label: 'Sales history', description: 'Review completed sales', href: '/pharmacy/sales', icon: ClipboardDocumentListIcon, bg: 'bg-violet-50', color: 'text-violet-600' }];
</script>
