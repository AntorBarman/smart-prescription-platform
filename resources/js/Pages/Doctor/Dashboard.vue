<template>
    <AppShell title="Doctor dashboard" subtitle="Your practice overview">
        <template #sidebar>
            <div class="flex h-full flex-col">
                <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                        <HeartIcon class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-sm font-bold tracking-tight text-slate-900">MediPrescribe</p>
                        <p class="text-[11px] text-slate-500">Doctor workspace</p>
                    </div>
                </div>
                <nav class="flex-1 space-y-1 px-3 py-5">
                    <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Workspace</p>
                    <Link href="/doctor/dashboard" class="flex items-center gap-3 rounded-lg bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm">
                        <HomeIcon class="h-5 w-5" /> Overview
                    </Link>
                    <Link href="/patients" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-700">
                        <UsersIcon class="h-5 w-5" /> Patients
                    </Link>
                    <Link href="/prescriptions" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-700">
                        <ClipboardDocumentListIcon class="h-5 w-5" /> Prescriptions
                    </Link>
                    <Link href="/medicines" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-700">
                        <BeakerIcon class="h-5 w-5" /> Medicines
                    </Link>
                </nav>
                <div class="border-t border-slate-100 p-4">
                    <form @submit.prevent="logout">
                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                            <ArrowRightOnRectangleIcon class="h-5 w-5" /> Sign out
                        </button>
                    </form>
                </div>
            </div>
        </template>

        <template #topbar>
            <div class="flex flex-1 items-center justify-between gap-4">
                <div class="hidden items-center gap-2 text-sm text-slate-500 sm:flex">
                    <span>Workspace</span><ChevronRightIcon class="h-4 w-4" /><span class="font-semibold text-slate-900">Overview</span>
                </div>
                <div class="ml-auto flex items-center gap-2 sm:gap-4">
                    <label class="hidden items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400 md:flex">
                        <MagnifyingGlassIcon class="h-4 w-4" /><input class="w-44 border-0 bg-transparent p-0 text-sm outline-none placeholder:text-slate-400 focus:ring-0" placeholder="Search patients..." />
                    </label>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600" aria-label="Notifications">
                        <BellIcon class="h-5 w-5" />
                    </button>
                    <div class="hidden h-8 w-px bg-slate-200 sm:block" />
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">{{ initials }}</div>
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold text-slate-900">{{ $page.props.auth.user.name }}</p>
                            <p class="text-[11px] text-slate-500">Doctor</p>
                        </div>
                        <ChevronDownIcon class="hidden h-4 w-4 text-slate-400 sm:block" />
                    </div>
                </div>
            </div>
        </template>

        <section class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-sm font-medium text-indigo-600">Good morning, Dr. {{ $page.props.auth.user.name }}</p>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Practice overview</h1>
                <p class="mt-1 text-sm text-slate-500">Stay on top of your patients and prescriptions.</p>
            </div>
            <Link href="/prescriptions/create" class="inline-flex w-fit items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-700">
                <PlusIcon class="h-4 w-4" /> New prescription
            </Link>
        </section>

        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div v-for="stat in statCards" :key="stat.label" class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="mb-5 flex items-center justify-between">
                    <div :class="['flex h-10 w-10 items-center justify-center rounded-lg', stat.iconBg]"><component :is="stat.icon" :class="['h-5 w-5', stat.iconColor]" /></div>
                    <span class="text-xs font-medium text-emerald-600">Live</span>
                </div>
                <p class="text-2xl font-bold tracking-tight text-slate-900">{{ stat.value }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ stat.label }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div><h2 class="text-sm font-bold text-slate-900">Recent prescriptions</h2><p class="mt-0.5 text-xs text-slate-500">Your latest patient orders</p></div>
                    <Link href="/prescriptions" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all <ArrowRightIcon class="ml-1 inline h-3.5 w-3.5" /></Link>
                </div>
                <div v-if="recentPrescriptions.length" class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-slate-50 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                            <tr><th class="px-5 py-3">Prescription</th><th class="px-5 py-3">Patient</th><th class="px-5 py-3">Created</th><th class="px-5 py-3">Status</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="rx in recentPrescriptions" :key="rx.id" class="transition hover:bg-indigo-50/40">
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-indigo-600">{{ rx.prescription_number }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">{{ rx.patient?.name || 'Unknown patient' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">{{ formatDate(rx.created_at) }}</td>
                                <td class="whitespace-nowrap px-5 py-4"><StatusBadge :status="rx.status === 'issued' ? 'success' : 'warning'" :label="rx.status || 'Draft'" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="px-5 py-14 text-center"><ClipboardDocumentListIcon class="mx-auto h-9 w-9 text-slate-300" /><p class="mt-3 text-sm font-medium text-slate-700">No prescriptions yet</p><p class="mt-1 text-xs text-slate-500">Create your first prescription to see it here.</p></div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4"><h2 class="text-sm font-bold text-slate-900">Quick actions</h2><p class="mt-0.5 text-xs text-slate-500">Common tasks, one click away</p></div>
                <div class="space-y-2">
                    <Link v-for="action in actions" :key="action.label" :href="action.href" class="group flex items-center gap-3 rounded-lg border border-slate-100 p-3 transition hover:-translate-y-0.5 hover:border-indigo-100 hover:bg-indigo-50">
                        <span :class="['flex h-9 w-9 items-center justify-center rounded-lg', action.bg]"><component :is="action.icon" :class="['h-4 w-4', action.color]" /></span>
                        <span class="flex-1"><span class="block text-sm font-semibold text-slate-800">{{ action.label }}</span><span class="block text-xs text-slate-500">{{ action.description }}</span></span>
                        <ArrowRightIcon class="h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-indigo-500" />
                    </Link>
                </div>
            </section>
        </div>
    </AppShell>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon, ArrowRightOnRectangleIcon, BeakerIcon, BellIcon, CalendarDaysIcon, ChevronDownIcon, ChevronRightIcon, ClipboardDocumentListIcon, DocumentPlusIcon, HeartIcon, HomeIcon, MagnifyingGlassIcon, PlusIcon, UsersIcon, UserPlusIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';
import StatusBadge from '../../Components/StatusBadge.vue';

const props = defineProps({
    stats: { type: Object, default: () => ({ totalPatients: 0, todayPrescriptions: 0, totalPrescriptions: 0 }) },
    recentPrescriptions: { type: Array, default: () => [] },
});

const page = usePage();
const initials = computed(() => (page.props.auth.user.name || 'U').split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase());
const statCards = computed(() => [
    { label: 'Total patients', value: props.stats.totalPatients, icon: UsersIcon, iconBg: 'bg-indigo-50', iconColor: 'text-indigo-600' },
    { label: 'Prescriptions today', value: props.stats.todayPrescriptions, icon: CalendarDaysIcon, iconBg: 'bg-emerald-50', iconColor: 'text-emerald-600' },
    { label: 'Total prescriptions', value: props.stats.totalPrescriptions, icon: ClipboardDocumentListIcon, iconBg: 'bg-violet-50', iconColor: 'text-violet-600' },
]);
const actions = [
    { label: 'Create prescription', description: 'Start a new e-prescription', href: '/prescriptions/create', icon: DocumentPlusIcon, bg: 'bg-indigo-50', color: 'text-indigo-600' },
    { label: 'Find a patient', description: 'Open your patient directory', href: '/patients', icon: UserPlusIcon, bg: 'bg-emerald-50', color: 'text-emerald-600' },
    { label: 'Browse medicines', description: 'Review the medicine catalog', href: '/medicines', icon: BeakerIcon, bg: 'bg-amber-50', color: 'text-amber-600' },
];
const formatDate = (date) => date ? new Date(date).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
const logout = () => router.post('/logout');
</script>
