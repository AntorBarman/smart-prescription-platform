<template>
    <AppShell title="Patient profile" subtitle="Review patient information and history">
        <template #sidebar>
            <div class="flex h-full flex-col">
                <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white"><HeartIcon class="h-5 w-5" /></div><div><p class="text-sm font-bold text-slate-900">MediPrescribe</p><p class="text-[11px] text-slate-500">Doctor workspace</p></div></div>
                <nav class="flex-1 space-y-1 px-3 py-5"><Link href="/doctor/dashboard" class="nav-link"><HomeIcon class="h-5 w-5" /> Overview</Link><Link href="/patients" class="nav-link-active"><UsersIcon class="h-5 w-5" /> Patients</Link><Link href="/prescriptions" class="nav-link"><ClipboardDocumentListIcon class="h-5 w-5" /> Prescriptions</Link><Link href="/medicines" class="nav-link"><BeakerIcon class="h-5 w-5" /> Medicines</Link></nav>
            </div>
        </template>

        <div class="mb-6 flex items-center justify-between gap-3">
            <Link href="/patients" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600"><ArrowLeftIcon class="h-4 w-4" /> Back to patients</Link>
            <Link :href="`/patients/${patient.id}/edit`" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Edit profile</Link>
        </div>
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 text-xl font-bold text-indigo-700">{{ initials }}</div>
                <div><p class="text-2xl font-bold text-slate-900">{{ patient.name }}</p><p class="mt-1 text-sm text-slate-500">{{ age }} <span v-if="patient.gender">· {{ patient.gender }}</span><span v-if="patient.blood_group"> · {{ patient.blood_group }}</span></p></div>
            </div>
            <div class="mt-8 grid gap-5 border-t border-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-3">
                <div><p class="detail-label">Phone</p><p class="detail-value">{{ patient.phone || 'Not provided' }}</p></div>
                <div><p class="detail-label">Email</p><p class="detail-value">{{ patient.email || 'Not provided' }}</p></div>
                <div><p class="detail-label">Date of birth</p><p class="detail-value">{{ formatDate(patient.date_of_birth) }}</p></div>
                <div class="sm:col-span-2 lg:col-span-3"><p class="detail-label">Address</p><p class="detail-value">{{ patient.address || 'Not provided' }}</p></div>
                <div><p class="detail-label">Allergies</p><p class="detail-value">{{ patient.allergies || 'None recorded' }}</p></div>
                <div class="sm:col-span-2"><p class="detail-label">Medical history</p><p class="detail-value">{{ patient.medical_history || 'None recorded' }}</p></div>
            </div>
        </section>
    </AppShell>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, BeakerIcon, ClipboardDocumentListIcon, HeartIcon, HomeIcon, UsersIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';

const props = defineProps({ patient: { type: Object, required: true } });
const initials = computed(() => (props.patient.name || 'P').split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase());
const age = computed(() => {
    if (!props.patient.date_of_birth) return 'Age unavailable';
    const birth = new Date(props.patient.date_of_birth);
    const today = new Date();
    let years = today.getFullYear() - birth.getFullYear();
    if (today < new Date(today.getFullYear(), birth.getMonth(), birth.getDate())) years--;
    return `${years} years`;
});
const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'Not provided';
</script>

<style scoped>
@reference "../../../css/app.css";

.nav-link, .nav-link-active { @apply flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition; }
.nav-link { @apply text-slate-600 hover:bg-indigo-50 hover:text-indigo-700; }
.nav-link-active { @apply bg-indigo-600 font-semibold text-white shadow-sm; }
.detail-label { @apply text-xs font-semibold uppercase tracking-wide text-slate-400; }
.detail-value { @apply mt-1 text-sm text-slate-700; }
</style>
