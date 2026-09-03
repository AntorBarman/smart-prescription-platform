<template>
    <div class="min-h-screen bg-[#F7F8FA]">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14 items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 bg-indigo-600 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">MediPrescribe</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600">{{ $page.props.auth.user.name }}</span>
                        <form @submit.prevent="logout">
                            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 border border-gray-300 rounded-md px-3 py-1.5 transition">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-xl font-semibold text-gray-900">Welcome, Dr. {{ $page.props.auth.user.name }}!</h1>
                <p class="text-sm text-gray-500 mt-1">Here's your practice overview for today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <!-- Total Patients -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Total Patients</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalPatients }}</p>
                        </div>
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Today's Prescriptions -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Prescriptions Today</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.todayPrescriptions }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Prescriptions -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Total Prescriptions</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalPrescriptions }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <Link href="/prescriptions/create" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            <div class="w-8 h-8 bg-indigo-600 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Create New Prescription</p>
                                <p class="text-xs text-gray-500">Generate a new e-prescription</p>
                            </div>
                        </Link>
                        <Link href="/patients" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <div class="w-8 h-8 bg-green-600 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Search Patients</p>
                                <p class="text-xs text-gray-500">Find patient records</p>
                            </div>
                        </Link>
                        <Link href="/medicines" class="flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                            <div class="w-8 h-8 bg-yellow-600 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">View Medicines</p>
                                <p class="text-xs text-gray-500">Browse medicine catalog</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Recent Prescriptions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-semibold text-gray-900">Recent Prescriptions</h2>
                        <Link href="/prescriptions" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View all</Link>
                    </div>
                    <div v-if="recentPrescriptions.length > 0" class="space-y-3">
                        <div v-for="rx in recentPrescriptions" :key="rx.id" class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                            <div>
                                <p class="text-xs font-semibold text-gray-900">{{ rx.prescription_number }}</p>
                                <p class="text-xs text-gray-500">{{ rx.patient?.name }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ new Date(rx.created_at).toLocaleDateString() }}</span>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-400 text-center py-8">No prescriptions yet.</p>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalPatients: 0,
            todayPrescriptions: 0,
            totalPrescriptions: 0,
        }),
    },
    recentPrescriptions: {
        type: Array,
        default: () => [],
    },
});

const logout = () => {
    router.post('/logout');
};
</script>