<template>
    <AppShell title="Prescription details" subtitle="Digital prescription record">
        <template #sidebar><PrescriptionNav /></template>
        <template #topbar>
            <div class="no-print flex flex-1 items-center justify-between gap-3">
                <Link href="/prescriptions" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600"><ArrowLeftIcon class="h-4 w-4" /> Back</Link>
                <div class="flex items-center gap-2">
                    <button type="button" class="action-button" @click="printPrescription"><PrinterIcon class="h-4 w-4" /> <span class="hidden sm:inline">Print</span></button>
                    <button type="button" class="action-button" @click="downloadPdf"><ArrowDownTrayIcon class="h-4 w-4" /> <span class="hidden sm:inline">Download PDF</span></button>
                    <button type="button" class="action-button-primary" :disabled="qrLoading" @click="generateQR"><QrCodeIcon class="h-4 w-4" /> {{ qrLoading ? 'Generating...' : 'Generate QR' }}</button>
                </div>
            </div>
        </template>
        <article id="prescription-document" class="mx-auto max-w-[850px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="h-1.5 bg-indigo-600" />
            <header class="border-b border-slate-200 px-6 py-7 sm:px-10">
                <div class="flex flex-col justify-between gap-6 sm:flex-row">
                    <div><p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">MediPrescribe</p><h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ prescription.doctor?.name || 'Doctor' }}</h1><p class="mt-1 text-sm text-slate-500">{{ prescription.doctor?.specialty || 'General Physician' }}</p><p class="mt-2 text-xs text-slate-400">Registration No. 12345</p></div>
                    <div class="sm:text-right"><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Prescription</p><p class="mt-1 text-lg font-bold text-indigo-600">{{ prescription.prescription_number }}</p><p class="mt-2 text-sm text-slate-500">{{ formatDate(prescription.created_at) }}</p></div>
                </div>
            </header>
            <section class="grid border-b border-slate-200 sm:grid-cols-2">
                <div class="px-6 py-5 sm:px-10"><p class="label">Patient</p><p class="mt-1 text-base font-bold text-slate-900">{{ prescription.patient?.name }}</p><p class="mt-1 text-sm capitalize text-slate-500">{{ prescription.patient?.gender || '—' }} · {{ prescription.patient?.phone || 'No phone' }}</p></div>
                <div class="border-t border-slate-200 px-6 py-5 sm:border-l sm:border-t-0 sm:px-10"><p class="label">Clinical summary</p><p class="mt-1 text-sm font-semibold text-slate-900">{{ prescription.diagnosis || 'No diagnosis specified' }}</p><p class="mt-1 text-sm text-slate-500">Issued {{ formatDate(prescription.created_at) }}</p></div>
            </section>
            <section class="px-6 py-7 sm:px-10">
                <div class="mb-4 flex items-center gap-2"><span class="text-xl font-serif text-indigo-600">℞</span><div><h2 class="text-base font-bold text-slate-900">Prescribed medicines</h2><p class="text-xs text-slate-500">Follow the dosage instructions carefully.</p></div></div>
                <table v-if="prescription.items?.length" class="hidden w-full text-left sm:table"><thead><tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400"><th class="pb-3">Medicine</th><th class="pb-3">Dosage</th><th class="pb-3">Duration</th><th class="pb-3 text-right">Quantity</th></tr></thead><tbody><tr v-for="(item, index) in prescription.items" :key="item.id" class="border-b border-slate-100 last:border-0"><td class="py-4 pr-4"><p class="text-sm font-semibold text-slate-900">{{ String(index + 1).padStart(2, '0') }}. {{ item.medicine?.name }}</p><p class="mt-1 text-xs text-slate-500">{{ item.medicine?.strength }} · {{ item.medicine?.dosage_form }}</p><p v-if="item.instructions" class="mt-1 text-xs text-slate-400">{{ item.instructions }}</p></td><td class="py-4 pr-4"><span class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ item.dosage }}</span></td><td class="py-4 text-sm text-slate-600">{{ item.duration_days }} days</td><td class="py-4 text-right text-sm font-bold text-slate-900">{{ item.quantity }} pcs</td></tr></tbody></table>
                <div class="space-y-3 sm:hidden"><div v-for="(item, index) in prescription.items" :key="item.id" class="rounded-lg border border-slate-200 p-4"><div class="flex items-start justify-between gap-3"><div><p class="text-sm font-semibold text-slate-900">{{ String(index + 1).padStart(2, '0') }}. {{ item.medicine?.name }}</p><p class="mt-1 text-xs text-slate-500">{{ item.medicine?.strength }} · {{ item.medicine?.dosage_form }}</p></div><span class="text-xs font-bold text-slate-900">{{ item.quantity }} pcs</span></div><div class="mt-3 flex items-center gap-3 text-xs text-slate-600"><span class="rounded-md bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">{{ item.dosage }}</span><span>{{ item.duration_days }} days</span></div><p v-if="item.instructions" class="mt-2 text-xs text-slate-500">{{ item.instructions }}</p></div></div>
            </section>
            <section v-if="prescription.notes || qrImageUrl" class="grid gap-6 border-t border-slate-200 px-6 py-6 sm:grid-cols-[1fr_auto] sm:px-10">
                <div><p class="label">Doctor's instructions</p><p class="mt-2 text-sm leading-6 text-slate-600">{{ prescription.notes || 'Take medicines as directed and follow up if symptoms persist.' }}</p><div class="mt-8 w-48 border-t border-slate-300 pt-2"><p class="text-sm font-semibold text-slate-900">{{ prescription.doctor?.name }}</p><p class="text-xs text-slate-500">Digital signature · Reg. No. 12345</p></div></div>
                <div v-if="qrImageUrl" class="text-center"><img :src="qrImageUrl" alt="Prescription verification QR code" class="mx-auto h-28 w-28 rounded-lg border border-slate-200 p-1" /><p class="mt-2 text-[11px] text-slate-500">Scan to verify</p></div>
            </section>
        </article>
        <p class="no-print mx-auto mt-4 max-w-[850px] text-center text-xs text-slate-400">MediPrescribe · Digital prescription platform · {{ prescription.prescription_number }}</p>
    </AppShell>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import QRCode from 'qrcode';
import { ArrowDownTrayIcon, ArrowLeftIcon, PrinterIcon, QrCodeIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';
import PrescriptionNav from '../../Components/PrescriptionNav.vue';
const props = defineProps({ prescription: Object });
const qrImageUrl = ref(null); const qrLoading = ref(false);
const formatDate = (date) => new Date(date).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
const generateQR = async () => { qrLoading.value = true; try { const response = await axios.get(`/prescriptions/${props.prescription.id}/qr`); if (response.data.success) qrImageUrl.value = await QRCode.toDataURL(response.data.data.qr_payload, { width: 220, margin: 1, errorCorrectionLevel: 'H' }); } finally { qrLoading.value = false; } };
const printPrescription = () => window.print();
const downloadPdf = () => window.print();
</script>

<style scoped>
@reference "../../../css/app.css";

.label { @apply text-[10px] font-semibold uppercase tracking-wider text-slate-400; }
.action-button, .action-button-primary { @apply inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold transition disabled:opacity-50; }
.action-button { @apply border border-slate-200 bg-white text-slate-700 hover:bg-slate-50; }
.action-button-primary { @apply bg-indigo-600 text-white hover:bg-indigo-700; }
@media print {
    :global(body) { background: white !important; }
    :global(body *) { visibility: hidden; }
    #prescription-document, #prescription-document * { visibility: visible; }
    #prescription-document { position: absolute; inset: 0; width: 100%; max-width: none; border: 0 !important; border-radius: 0 !important; box-shadow: none !important; }
    .no-print { display: none !important; }
}
</style>
