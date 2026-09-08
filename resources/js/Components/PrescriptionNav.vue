<template>
    <div class="flex h-full flex-col"><div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white"><HeartIcon class="h-5 w-5" /></div><div><p class="text-sm font-bold text-slate-900">MediPrescribe</p><p class="text-[11px] text-slate-500">Doctor workspace</p></div></div><nav class="flex-1 space-y-1 px-3 py-5"><Link v-for="item in items" :key="item.label" :href="item.href" :class="['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition', item.active ? 'bg-indigo-600 font-semibold text-white shadow-sm' : 'font-medium text-slate-600 hover:bg-indigo-50 hover:text-indigo-700']"><component :is="item.icon" class="h-5 w-5" />{{ item.label }}</Link></nav></div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import { BeakerIcon, ClipboardDocumentListIcon, HeartIcon, HomeIcon, UsersIcon } from '@heroicons/vue/24/outline';
const items = [{ label: 'Overview', href: '/doctor/dashboard', icon: HomeIcon }, { label: 'Patients', href: '/patients', icon: UsersIcon }, { label: 'Prescriptions', href: '/prescriptions', icon: ClipboardDocumentListIcon, active: true }, { label: 'Medicines', href: '/medicines', icon: BeakerIcon }];
</script>
