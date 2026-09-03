<template>
    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">MediPrescribe</p>
                            <p class="text-xs text-gray-500">Digital Prescription Platform</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link href="/login" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</Link>
                        <Link href="/register" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Get Started</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <div class="max-w-3xl mx-auto">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium mb-6">
                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Trusted by healthcare professionals
                </span>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight">
                    Smart Digital Prescription Platform
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    Create secure digital prescriptions with QR verification. Connect doctors, patients, and pharmacies in one seamless platform.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                    <Link href="/register" class="px-6 py-3 bg-indigo-600 text-white rounded-lg text-base font-medium hover:bg-indigo-700 transition">
                        Start Prescribing
                    </Link>
                    <Link href="/login" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 transition">
                        Sign In
                    </Link>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Built for Modern Healthcare</h2>
                    <p class="mt-2 text-gray-500">Everything you need to manage digital prescriptions</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Secure QR Verification</h3>
                        <p class="text-sm text-gray-500">Every prescription gets a unique QR code for instant verification at participating pharmacies.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Digital Records</h3>
                        <p class="text-sm text-gray-500">Maintain complete digital prescription history for all patients and medicines.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Patient Management</h3>
                        <p class="text-sm text-gray-500">Search, manage, and track patients efficiently with a modern interface.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Ready to Go Paperless?</h2>
            <p class="mt-2 text-gray-500">Join the future of healthcare with digital prescriptions.</p>
            <Link href="/register" class="inline-block mt-6 px-8 py-3 bg-indigo-600 text-white rounded-lg text-base font-medium hover:bg-indigo-700 transition">
                Get Started Today
            </Link>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm text-gray-400">© 2026 MediPrescribe. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>