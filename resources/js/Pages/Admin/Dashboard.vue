<template>
    <div class="min-h-screen bg-[#F8FAFC] flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-slate-200 fixed inset-y-0 z-20">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">MediPrescribe</p>
                        <p class="text-xs text-slate-500">Admin Panel</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 py-4">
                <div class="px-3 mb-2"><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Main</p></div>
                <a class="flex items-center px-4 py-2.5 text-sm bg-indigo-50 text-indigo-700 font-medium rounded-lg mx-2 border-l-2 border-indigo-600">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
                <Link href="/medicines" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    Medicines
                </Link>
                <Link href="/patients" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Patients
                </Link>
                <Link href="/prescriptions" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Prescriptions
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">System Overview</h1>
                        <p class="text-sm text-slate-500">Monitor platform performance and statistics</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2 border-r border-slate-200 pr-4">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-medium">A</div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-slate-500">Administrator</p>
                            </div>
                        </div>
                        <form @submit.prevent="logout">
                            <button type="submit" class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-700 border border-slate-300 rounded-md">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-6 py-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-xs text-green-600 font-medium">Active</span>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.totalDoctors }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Doctors</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.totalPharmacies }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Pharmacies</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.totalPatients }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Patients</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 bg-yellow-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.totalMedicines }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Medicines</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.totalPrescriptions }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Prescriptions</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-indigo-600">{{ stats.todayPrescriptions }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Today's Rx</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Link href="/medicines" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Manage Medicines</p>
                        <p class="text-xs text-slate-500 mt-1">View, add, edit medicine catalog</p>
                    </Link>
                    <Link href="/patients" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-100 transition">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Manage Patients</p>
                        <p class="text-xs text-slate-500 mt-1">View all patient records</p>
                    </Link>
                    <Link href="/prescriptions" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-indigo-300 hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-100 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">All Prescriptions</p>
                        <p class="text-xs text-slate-500 mt-1">View system-wide prescriptions</p>
                    </Link>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalDoctors: 0,
            totalPharmacies: 0,
            totalPatients: 0,
            totalMedicines: 0,
            totalPrescriptions: 0,
            todayPrescriptions: 0,
        }),
    },
});

const logout = () => {
    router.post('/logout');
};
</script>