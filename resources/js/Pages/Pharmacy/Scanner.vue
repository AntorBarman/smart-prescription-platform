<template>
    <div class="min-h-screen bg-[#F8FAFC]">
        <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
            <div class="max-w-3xl mx-auto flex justify-between items-center px-6 py-4">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">QR Scanner</h1>
                    <p class="text-sm text-slate-500">Scan prescription QR code</p>
                </div>
                <form @submit.prevent="logout">
                    <button type="submit" class="px-3 py-1.5 text-xs text-slate-500 border border-slate-300 rounded-md">Logout</button>
                </form>
            </div>
        </header>

        <main class="max-w-3xl mx-auto px-6 py-8">
            <!-- Camera Scanner -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">Scan QR Code</h2>
                
                <button @click="toggleCamera" class="w-full py-3 px-4 rounded-lg text-sm font-medium transition" :class="cameraActive ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                    <svg v-if="!cameraActive" class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                    <svg v-if="cameraActive" class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ cameraActive ? 'Stop Camera' : 'Start Camera Scanner' }}
                </button>

                <div v-if="cameraActive" class="mt-4 relative">
                    <video ref="videoRef" class="w-full rounded-lg border border-slate-200" autoplay playsinline></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-48 h-48 border-2 border-indigo-500 rounded-lg opacity-70"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-slate-400 mb-2">Or paste QR content manually:</p>
                    <div class="flex gap-2">
                        <input v-model="qrInput" type="text" placeholder="Paste QR content..." class="flex-1 px-3 py-2 border border-slate-300 rounded-lg text-sm" @keyup.enter="processQR(qrInput)" />
                        <button @click="processQR(qrInput)" :disabled="processing" class="px-4 py-2 bg-slate-600 text-white rounded-lg text-sm font-medium hover:bg-slate-700 disabled:opacity-50">
                            Verify
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-red-800">{{ error }}</p>
            </div>

            <!-- Success Message -->
            <div v-if="success" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-green-800">{{ success }}</p>
            </div>

            <!-- Prescription Details with Pricing -->
            <div v-if="result" class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-green-50 border-b border-green-200 flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-sm font-semibold text-green-800">Prescription Verified Successfully</span>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-900">{{ result.prescription.prescription_number }}</p>
                            <p class="text-xs text-slate-500">{{ new Date(result.prescription.created_at).toLocaleDateString() }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ result.prescription.status }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Patient</p>
                            <p class="text-sm font-semibold text-slate-900">{{ result.prescription.patient?.name }}</p>
                            <p class="text-xs text-slate-500">{{ result.prescription.patient?.phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500">Doctor</p>
                            <p class="text-sm font-semibold text-slate-900">{{ result.prescription.doctor?.name }}</p>
                        </div>
                    </div>

                    <!-- Medicines with Price -->
                    <div class="border-t border-slate-200 pt-4">
                        <p class="text-sm font-semibold text-slate-900 mb-3">Medicines</p>
                        <div v-for="item in result.items" :key="item.id" class="flex justify-between items-center py-3 border-b border-slate-100 last:border-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">{{ item.medicine_name }} ({{ item.strength }})</p>
                                <p class="text-xs text-slate-500">{{ item.dosage }} • {{ item.duration_days }} days</p>
                                <p class="text-xs font-medium mt-0.5" :class="item.is_available ? 'text-green-600' : 'text-red-600'">
                                    {{ item.is_available ? '✓ In Stock (' + item.in_stock + ')' : '✗ Out of Stock (' + item.in_stock + ')' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500">{{ item.quantity }} × ৳{{ item.unit_price }}</p>
                                <p class="text-sm font-semibold text-slate-900">৳{{ item.total_price }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Summary -->
                    <div class="border-t border-slate-200 mt-4 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-medium text-slate-900">৳{{ result.pricing.subtotal }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Tax (5%)</span>
                            <span class="font-medium text-slate-900">৳{{ result.pricing.tax }}</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 pt-2">
                            <span class="font-semibold text-slate-900">Grand Total</span>
                            <span class="font-bold text-indigo-600 text-lg">৳{{ result.pricing.grand_total }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-6">
                        <Link :href="`/pharmacy/sales/create?prescription_id=${result.prescription.id}`" class="block w-full text-center px-4 py-3 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                            Proceed to Billing
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { BrowserMultiFormatReader } from '@zxing/browser';

const qrInput = ref('');
const processing = ref(false);
const error = ref('');
const success = ref('');
const result = ref(null);
const cameraActive = ref(false);
const videoRef = ref(null);
const codeReader = new BrowserMultiFormatReader();

let videoStream = null;

const toggleCamera = async () => {
    if (cameraActive.value) {
        stopCamera();
    } else {
        startCamera();
    }
};

const startCamera = async () => {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });

        if (videoRef.value) {
            videoRef.value.srcObject = videoStream;
            videoRef.value.play();
        }

        cameraActive.value = true;

        codeReader.decodeFromVideoDevice(undefined, videoRef.value, (result, err) => {
            if (result) {
                const text = result.getText();
                processQR(text);
                stopCamera();
            }
        });
    } catch (err) {
        error.value = 'Camera access denied or not available. Please use manual input.';
        console.error('Camera error:', err);
    }
};

const stopCamera = () => {
    cameraActive.value = false;
    
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    
    if (videoRef.value) {
        videoRef.value.srcObject = null;
    }
    
    codeReader.reset();
};

const processQR = async (qrContent) => {
    if (!qrContent || !qrContent.trim()) {
        error.value = 'QR content is empty.';
        return;
    }

    processing.value = true;
    error.value = '';
    success.value = '';
    result.value = null;

    try {
        const response = await axios.post('/api/qr/process', {
            qr_content: qrContent.trim(),
        });

        if (response.data.success) {
            result.value = response.data.data;
            success.value = 'Prescription verified successfully!';
            qrInput.value = '';
        } else {
            error.value = response.data.message || 'Verification failed.';
        }
    } catch (e) {
        error.value = e.response?.data?.message || 'Verification failed.';
    } finally {
        processing.value = false;
    }
};

const logout = () => {
    router.post('/logout');
};

onBeforeUnmount(() => {
    stopCamera();
});
</script>