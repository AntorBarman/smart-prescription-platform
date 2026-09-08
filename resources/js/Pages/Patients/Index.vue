<template>
    <AppShell title="Patients" subtitle="Manage your patient directory">
        <template #sidebar>
            <div class="flex h-full flex-col">
                <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white"><HeartIcon class="h-5 w-5" /></div><div><p class="text-sm font-bold text-slate-900">MediPrescribe</p><p class="text-[11px] text-slate-500">Doctor workspace</p></div></div>
                <nav class="flex-1 space-y-1 px-3 py-5"><p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Workspace</p><Link href="/doctor/dashboard" class="nav-link"><HomeIcon class="h-5 w-5" /> Overview</Link><Link href="/patients" class="nav-link-active"><UsersIcon class="h-5 w-5" /> Patients</Link><Link href="/prescriptions" class="nav-link"><ClipboardDocumentListIcon class="h-5 w-5" /> Prescriptions</Link><Link href="/medicines" class="nav-link"><BeakerIcon class="h-5 w-5" /> Medicines</Link></nav>
            </div>
        </template>
        <template #topbar><div class="flex flex-1 items-center justify-between"><div class="hidden items-center gap-2 text-sm text-slate-500 sm:flex"><span>Workspace</span><ChevronRightIcon class="h-4 w-4" /><span class="font-semibold text-slate-900">Patients</span></div><div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">{{ initials }}</div></div></template>

        <section class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="mb-2 text-sm font-medium text-indigo-600">Patient directory</p><h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Patients</h1><p class="mt-1 text-sm text-slate-500">Keep every patient record organized and accessible.</p></div><Link href="/patients/create" class="inline-flex w-fit items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-700"><PlusIcon class="h-4 w-4" /> Add patient</Link></section>
        <div v-if="$page.props.flash?.success" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ $page.props.flash.success }}</div>
        <section class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex flex-col gap-3 sm:flex-row"><label class="flex flex-1 items-center gap-2 rounded-lg border border-slate-300 px-3 py-2.5 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20"><MagnifyingGlassIcon class="h-5 w-5 text-slate-400" /><input v-model="search" type="search" class="min-w-0 flex-1 border-0 p-0 text-sm outline-none focus:ring-0" placeholder="Search by name, phone, or email..." @input="debouncedSearch" /></label><label class="flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-600"><FunnelIcon class="h-4 w-4 text-slate-400" /><select v-model="genderFilter" class="border-0 bg-transparent p-0 pr-7 text-sm outline-none focus:ring-0"><option value="all">All patients</option><option value="male">Male</option><option value="female">Female</option></select></label></div></section>
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><div><h2 class="text-sm font-bold text-slate-900">Patient records</h2><p class="mt-0.5 text-xs text-slate-500">{{ filteredPatients.length }} patients in this view</p></div><span class="hidden text-xs text-slate-500 sm:block">Sorted alphabetically</span></div>
            <div v-if="filteredPatients.length" class="hidden overflow-x-auto md:block"><table class="min-w-full text-left"><thead class="bg-slate-50 text-[11px] font-semibold uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Patient</th><th class="px-5 py-3">Phone</th><th class="px-5 py-3">Gender</th><th class="px-5 py-3">Blood group</th><th class="px-5 py-3">Age</th><th class="px-5 py-3 text-right"> </th></tr></thead><tbody class="divide-y divide-slate-100"><tr v-for="patient in filteredPatients" :key="patient.id" class="transition hover:bg-indigo-50/40"><td class="px-5 py-4"><div class="flex items-center gap-3"><span :class="['flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold', avatarClass(patient.name)]">{{ initialsFor(patient.name) }}</span><span class="text-sm font-semibold text-slate-900">{{ patient.name }}</span></div></td><td class="px-5 py-4 text-sm text-slate-500">{{ patient.phone || '—' }}</td><td class="px-5 py-4"><StatusBadge :status="patient.gender === 'female' ? 'info' : 'success'" :label="patient.gender || 'Not specified'" /></td><td class="px-5 py-4 text-sm text-slate-500">{{ patient.blood_group || '—' }}</td><td class="px-5 py-4 text-sm text-slate-500">{{ calculateAge(patient.date_of_birth) }}</td><td class="px-5 py-4 text-right"><Link :href="`/patients/${patient.id}`" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700">View profile <ArrowRightIcon class="h-4 w-4" /></Link></td></tr></tbody></table></div>
            <div v-if="filteredPatients.length" class="divide-y divide-slate-100 md:hidden">
                <div v-for="patient in filteredPatients" :key="patient.id" class="p-4">
                    <div class="flex items-start gap-3">
                        <span :class="['flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold', avatarClass(patient.name)]">{{ initialsFor(patient.name) }}</span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div><p class="truncate text-sm font-semibold text-slate-900">{{ patient.name }}</p><p class="mt-0.5 text-xs text-slate-500">{{ patient.phone || 'No phone' }} · {{ calculateAge(patient.date_of_birth) }}</p></div>
                                <StatusBadge :status="patient.gender === 'female' ? 'info' : 'success'" :label="patient.gender || 'Other'" />
                            </div>
                            <Link :href="`/patients/${patient.id}`" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-indigo-600">View profile <ArrowRightIcon class="h-3.5 w-3.5" /></Link>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!filteredPatients.length" class="px-5 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600"><UserGroupIcon class="h-7 w-7" /></span><h3 class="mt-4 text-base font-bold text-slate-900">No patients found</h3><p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Try adjusting your search or add a new patient record to get started.</p><Link href="/patients/create" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"><PlusIcon class="h-4 w-4" /> Add patient</Link></div>
        </section>
        <div v-if="patients.total > patients.per_page" class="mt-4 text-sm text-slate-500">Showing {{ patients.from }} to {{ patients.to }} of {{ patients.total }} patients</div>
    </AppShell>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon, BeakerIcon, ChevronRightIcon, ClipboardDocumentListIcon, FunnelIcon, HeartIcon, HomeIcon, MagnifyingGlassIcon, PlusIcon, UserGroupIcon, UsersIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';
import StatusBadge from '../../Components/StatusBadge.vue';

const props = defineProps({ patients: { type: Object, default: () => ({ data: [], total: 0, per_page: 10 }) }, filters: { type: Object, default: () => ({}) } });
const page = usePage();
const search = ref(props.filters.search || '');
const genderFilter = ref('all');
let timeout;
const initials = computed(() => initialsFor(page.props.auth.user.name));
const filteredPatients = computed(() => (props.patients.data || []).filter((patient) => genderFilter.value === 'all' || patient.gender === genderFilter.value));
const initialsFor = (name) => (name || 'U').split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();
const avatarClass = (name) => ['bg-indigo-100 text-indigo-700', 'bg-emerald-100 text-emerald-700', 'bg-violet-100 text-violet-700', 'bg-amber-100 text-amber-700'][((name || '').length) % 4];
const calculateAge = (dob) => { if (!dob) return '—'; const birthDate = new Date(dob); const today = new Date(); let age = today.getFullYear() - birthDate.getFullYear(); if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) age--; return `${age} years`; };
const debouncedSearch = () => { clearTimeout(timeout); timeout = setTimeout(() => router.get('/patients', { search: search.value }, { preserveState: true, replace: true }), 500); };
</script>

<style scoped>
@reference "../../../css/app.css";

.nav-link, .nav-link-active { @apply flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition; }
.nav-link { @apply text-slate-600 hover:bg-indigo-50 hover:text-indigo-700; }
.nav-link-active { @apply bg-indigo-600 font-semibold text-white shadow-sm; }
</style>
