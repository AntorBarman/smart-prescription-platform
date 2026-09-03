<template>
    <div class="min-h-screen bg-[#F7F8FA]">
        <!-- Top Action Toolbar -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-[850px] mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
                <Link href="/prescriptions" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="flex items-center space-x-2">
                    <button @click="printPrescription" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print
                    </button>
                    <button @click="generateQR" :disabled="qrLoading" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-indigo-600 rounded-lg text-xs font-medium text-white hover:bg-indigo-700 transition disabled:opacity-50">
                        <svg v-if="!qrLoading" class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <svg v-else class="w-3.5 h-3.5 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        {{ qrLoading ? 'Generating...' : 'Generate QR' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Prescription Document -->
        <div id="prescription-document" class="max-w-[850px] mx-auto px-4 sm:px-6 py-8">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                
                <!-- Header -->
                <div class="border-b border-gray-200 px-8 py-6 bg-white relative">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600"></div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 bg-indigo-50 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">MediPrescribe</p>
                                <p class="text-xs text-gray-500">Digital Prescription Platform</p>
                            </div>
                        </div>
                        <div class="text-right mt-3 sm:mt-0">
                            <p class="text-sm font-semibold text-gray-900">{{ prescription.prescription_number }}</p>
                            <span class="inline-flex items-center mt-1">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                <span class="text-xs font-medium text-green-700">Valid Prescription</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Doctor & Patient -->
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-200 border-b border-gray-200">
                    <div class="px-8 py-5">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Doctor</p>
                        <p class="text-sm font-semibold text-gray-900">{{ prescription.doctor?.name }}</p>
                        <p class="text-xs text-gray-500">{{ prescription.doctor?.specialty || 'General Physician' }}</p>
                        <p class="text-xs text-gray-400 mt-1.5">Reg. No: 12345</p>
                    </div>
                    <div class="px-8 py-5">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Patient</p>
                        <p class="text-sm font-semibold text-gray-900">{{ prescription.patient?.name }}</p>
                        <p class="text-xs text-gray-500">{{ prescription.patient?.gender || 'Male' }}</p>
                        <p class="text-xs text-gray-400 mt-1.5">{{ prescription.patient?.phone }}</p>
                    </div>
                </div>

                <!-- Clinical Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-8 py-4 bg-gray-50 border-b border-gray-200">
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Diagnosis</p>
                        <p class="text-sm text-gray-900 font-medium">{{ prescription.diagnosis || 'Not specified' }}</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                        <p class="text-sm text-gray-900">{{ formatDate(prescription.created_at) }}</p>
                    </div>
                </div>

                <!-- QR Verification -->
                <div v-if="qrImageUrl" class="px-8 py-5 border-b border-gray-200 flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <img :src="qrImageUrl" alt="QR Code" class="w-20 h-20 rounded-md border border-gray-200" />
                    <div class="text-center sm:text-left">
                        <p class="text-sm font-semibold text-gray-900 flex items-center justify-center sm:justify-start">
                            <svg class="w-4 h-4 text-green-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Verified Prescription
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Scan to verify authenticity</p>
                        <p class="text-xs text-gray-400 mt-0.5">Verification available 24/7</p>
                    </div>
                </div>

                <!-- Medicines -->
                <div class="px-8 py-6">
                    <div class="flex items-center mb-4">
                        <span class="text-base font-bold text-gray-900 mr-2">℞</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Prescribed Medicines</p>
                            <p class="text-xs text-gray-500">Medication instructions</p>
                        </div>
                    </div>

                    <div v-if="prescription.items && prescription.items.length > 0">
                        <!-- Desktop Table -->
                        <div class="hidden sm:block">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Medicine</th>
                                        <th class="text-left py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Schedule</th>
                                        <th class="text-left py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Duration</th>
                                        <th class="text-right py-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in prescription.items" :key="item.id" class="border-b border-gray-100 last:border-0">
                                        <td class="py-3 pr-4">
                                            <p class="text-sm font-semibold text-gray-900">{{ String(index + 1).padStart(2, '0') }}. {{ item.medicine?.name }}</p>
                                            <p class="text-xs text-gray-500">{{ item.medicine?.strength }} • {{ item.medicine?.dosage_form }}</p>
                                            <p v-if="item.instructions" class="text-xs text-gray-400 mt-1 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ item.instructions }}
                                            </p>
                                        </td>
                                        <td class="py-3 pr-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded bg-indigo-50 text-indigo-700 text-xs font-medium">{{ item.dosage }}</span>
                                        </td>
                                        <td class="py-3 pr-4 text-sm text-gray-700">{{ item.duration_days }} days</td>
                                        <td class="py-3 text-right">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded bg-gray-50 text-gray-900 text-xs font-semibold">{{ item.quantity }} pcs</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="sm:hidden space-y-3">
                            <div v-for="(item, index) in prescription.items" :key="item.id" class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ String(index + 1).padStart(2, '0') }}. {{ item.medicine?.name }}</span>
                                    <span class="text-xs font-semibold text-gray-700 bg-gray-50 px-2 py-0.5 rounded">{{ item.quantity }} pcs</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-2">{{ item.medicine?.strength }} • {{ item.medicine?.dosage_form }}</p>
                                <div class="space-y-1 text-xs text-gray-600">
                                    <p><span class="font-medium">Schedule:</span> {{ item.dosage }}</p>
                                    <p><span class="font-medium">Duration:</span> {{ item.duration_days }} days</p>
                                    <p v-if="item.instructions"><span class="font-medium">Instructions:</span> {{ item.instructions }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-400">No medicines prescribed</p>
                </div>

                <!-- Notes -->
                <div v-if="prescription.notes" class="px-8 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Doctor's Instructions</p>
                    <p class="text-sm text-gray-700">{{ prescription.notes }}</p>
                </div>

                <!-- Footer -->
                <div class="px-8 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
                    <p class="text-[10px] text-gray-400">This is a computer-generated prescription.<br>No physical signature is required for digitally verified prescriptions.</p>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ prescription.doctor?.name }}</p>
                        <p class="text-xs text-gray-500">General Physician • Reg. No: 12345</p>
                    </div>
                </div>
            </div>

            <!-- Document Meta -->
            <p class="text-center text-[10px] text-gray-400 mt-4">
                MediPrescribe • Digital Prescription Platform • {{ prescription.prescription_number }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import QRCode from 'qrcode';

const props = defineProps({
    prescription: Object,
});

const qrImageUrl = ref(null);
const qrLoading = ref(false);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
};

const generateQR = async () => {
    qrLoading.value = true;
    try {
        const response = await axios.get(`/prescriptions/${props.prescription.id}/qr`);
        if (response.data.success) {
            const payload = response.data.data.qr_payload;
            const dataUrl = await QRCode.toDataURL(payload, {
                width: 200,
                margin: 1,
                errorCorrectionLevel: 'H',
                color: { dark: '#000000', light: '#FFFFFF' },
            });
            qrImageUrl.value = dataUrl;
        }
    } catch (error) {
        console.error('QR Error:', error);
    } finally {
        qrLoading.value = false;
    }
};

const printPrescription = () => {
    window.print();
};
</script>

<style scoped>
@media print {
    body * {
        visibility: hidden;
    }
    #prescription-document, #prescription-document * {
        visibility: visible;
    }
    #prescription-document {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100%;
        padding: 0;
    }
    #prescription-document > div {
        box-shadow: none !important;
        border-radius: 0 !important;
        border: none !important;
    }
}
</style>