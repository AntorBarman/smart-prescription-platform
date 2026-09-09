<template>
    <AppShell title="Verify prescription" subtitle="Scan a prescription securely">
        <template #sidebar><PharmacyNav active="scanner" /></template>
        <template #topbar><div class="flex flex-1 items-center justify-between"><Link href="/pharmacy/dashboard" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600"><ArrowLeftIcon class="h-4 w-4" /> Back to overview</Link><div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">P</div></div></template>
        <div class="mx-auto max-w-3xl">
            <div class="mb-8"><p class="mb-2 text-sm font-medium text-indigo-600">Prescription verification</p><h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Verify prescription</h1><p class="mt-1 text-sm text-slate-500">Scan the patient's QR code or enter the code manually.</p></div>
            <section v-if="!result" class="rounded-xl border border-slate-800 bg-slate-950 p-4 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center justify-between"><div><h2 class="text-base font-bold text-white">QR scanner</h2><p class="mt-1 text-xs text-slate-400">Position the code inside the frame.</p></div><span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-500/15 px-2.5 py-1 text-xs font-medium text-indigo-300"><span class="h-1.5 w-1.5 rounded-full bg-indigo-400" /> Secure scan</span></div>
                <div class="relative flex min-h-[280px] items-center justify-center overflow-hidden rounded-xl bg-slate-900 ring-1 ring-inset ring-slate-700">
                    <video v-if="cameraActive" ref="videoRef" class="absolute inset-0 h-full w-full object-cover" autoplay playsinline></video>
                    <div class="relative z-10 h-52 w-52 rounded-2xl border-2 border-indigo-500 shadow-[0_0_28px_rgba(99,102,241,0.5)]"><span class="corner left-0 top-0" /><span class="corner right-0 top-0 rotate-90" /><span class="corner bottom-0 left-0 -rotate-90" /><span class="corner bottom-0 right-0 rotate-180" /><div v-if="cameraActive" class="scan-line" /></div>
                    <div v-if="!cameraActive" class="absolute bottom-5 z-10 text-center"><QrCodeIcon class="mx-auto h-8 w-8 text-slate-500" /><p class="mt-2 text-xs text-slate-400">Camera is ready when you are</p></div>
                </div>
                <button type="button" :class="['mt-4 flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-semibold transition', cameraActive ? 'bg-rose-500/10 text-rose-300 ring-1 ring-rose-400/30 hover:bg-rose-500/20' : 'bg-indigo-600 text-white hover:bg-indigo-500']" @click="toggleCamera"><VideoCameraIcon class="h-5 w-5" />{{ cameraActive ? 'Stop camera' : 'Start camera' }}</button>
                <div class="my-6 flex items-center gap-3 text-xs text-slate-500"><span class="h-px flex-1 bg-slate-800" />OR ENTER MANUALLY<span class="h-px flex-1 bg-slate-800" /></div>
                <div class="flex flex-col gap-2 sm:flex-row"><input v-model="qrInput" type="text" placeholder="Paste prescription code..." class="min-w-0 flex-1 rounded-lg border border-slate-700 bg-slate-900 px-3 py-3 text-sm text-white outline-none placeholder:text-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30" @keyup.enter="processQR(qrInput)" /><button type="button" :disabled="processing" class="rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-indigo-50 disabled:opacity-50" @click="processQR(qrInput)">{{ processing ? 'Verifying...' : 'Verify code' }}</button></div>
            </section>
            <div v-if="error" class="mt-5 flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"><ExclamationCircleIcon class="h-5 w-5" />{{ error }}</div>
            <section v-if="result" class="overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-sm">
                <div class="flex flex-col items-center border-b border-emerald-100 bg-emerald-50 px-6 py-8 text-center"><span class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"><CheckCircleIcon class="h-8 w-8" /></span><h2 class="mt-4 text-lg font-bold text-emerald-900">Prescription verified</h2><p class="mt-1 text-sm text-emerald-700">This prescription is valid and ready for fulfilment.</p></div>
                <div class="p-5 sm:p-6"><div class="grid gap-4 rounded-lg bg-slate-50 p-4 sm:grid-cols-3"><div><p class="text-xs text-slate-500">Patient</p><p class="mt-1 text-sm font-bold text-slate-900">{{ result.prescription.patient?.name }}</p><p class="text-xs text-slate-500">{{ result.prescription.patient?.phone }}</p></div><div><p class="text-xs text-slate-500">Prescription ID</p><p class="mt-1 text-sm font-bold text-indigo-600">{{ result.prescription.prescription_number }}</p></div><div><p class="text-xs text-slate-500">Issued by</p><p class="mt-1 text-sm font-bold text-slate-900">{{ result.prescription.doctor?.name }}</p></div></div><div class="mt-6"><h3 class="text-sm font-bold text-slate-900">Medicines</h3><div v-for="item in result.items" :key="item.id" class="flex items-center justify-between border-b border-slate-100 py-3 last:border-0"><div><p class="text-sm font-semibold text-slate-900">{{ item.medicine_name }} <span class="font-normal text-slate-500">({{ item.strength }})</span></p><p class="mt-0.5 text-xs text-slate-500">{{ item.dosage }} · {{ item.duration_days }} days · {{ item.quantity }} pcs</p></div><span :class="item.is_available ? 'text-emerald-600' : 'text-rose-600'" class="text-xs font-semibold">{{ item.is_available ? 'In stock' : 'Out of stock' }}</span></div></div><div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-4"><span class="text-sm font-semibold text-slate-600">Grand total</span><span class="text-xl font-bold text-slate-900">৳{{ result.pricing.grand_total }}</span></div><Link :href="`/pharmacy/sales/create?prescription_id=${result.prescription.id}`" class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Proceed to billing <ArrowRightIcon class="h-4 w-4" /></Link><button type="button" class="mt-3 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="result = null">Scan another prescription</button></div>
            </section>
        </div>
    </AppShell>
</template>

<script setup>
import { ref, nextTick, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { BrowserMultiFormatReader } from '@zxing/browser';
import { ArrowLeftIcon, ArrowRightIcon, CheckCircleIcon, ExclamationCircleIcon, QrCodeIcon, VideoCameraIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';
import PharmacyNav from '../../Components/PharmacyNav.vue';
const qrInput = ref(''); const processing = ref(false); const error = ref(''); const result = ref(null); const cameraActive = ref(false); const videoRef = ref(null); const codeReader = new BrowserMultiFormatReader(); let scannerControls = null;
const toggleCamera = () => cameraActive.value ? stopCamera() : startCamera();
const startCamera = async () => {
    try {
        if (!navigator.mediaDevices?.getUserMedia) {
            throw new Error('Camera API unavailable');
        }

        error.value = '';
        cameraActive.value = true;
        await nextTick();

        if (!videoRef.value) {
            throw new Error('Video preview could not be initialized');
        }

        scannerControls = await codeReader.decodeFromConstraints({
            video: { facingMode: { ideal: 'environment' } },
            audio: false,
        }, videoRef.value, (scan) => {
            if (scan && !processing.value) {
                processQR(scan.getText());
                stopCamera();
            }
        });
    } catch (err) {
        stopCamera();
        error.value = 'Camera access denied or unavailable. Please use manual input.';
    }
};
const stopCamera = () => {
    scannerControls?.stop();
    scannerControls = null;
    cameraActive.value = false;
    if (videoRef.value) videoRef.value.srcObject = null;
};
const processQR = async (content) => { if (!content?.trim()) { error.value = 'Enter a prescription code to verify.'; return; } processing.value = true; error.value = ''; result.value = null; try { const response = await axios.post('/api/qr/process', { qr_content: content.trim() }); if (response.data.success) { result.value = response.data.data; qrInput.value = ''; } else error.value = response.data.message || 'Verification failed.'; } catch (err) { error.value = err.response?.data?.message || 'Verification failed.'; } finally { processing.value = false; } };
onBeforeUnmount(stopCamera);
</script>

<style scoped>
@reference "../../../css/app.css";

.corner { @apply absolute h-5 w-5 border-indigo-300; }
.corner:nth-child(1) { border-left-width: 3px; border-top-width: 3px; border-top-left-radius: .5rem; }
.scan-line { position: absolute; left: 10%; right: 10%; top: 10%; height: 2px; background: #818cf8; box-shadow: 0 0 12px #6366f1; animation: scan 2s ease-in-out infinite; }
@keyframes scan { 50% { transform: translateY(170px); } }
</style>
