<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">Patients</h1>
                    <Link href="/patients/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                        Add Patient
                    </Link>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Search -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <input v-model="search" type="text" placeholder="Search patients by name, phone, email..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" @input="debouncedSearch" />
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 rounded-md bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Patient Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blood Group</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Age</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="patient in patients.data" :key="patient.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ patient.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ patient.phone || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ patient.gender || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ patient.blood_group || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ calculateAge(patient.date_of_birth) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Link :href="`/patients/${patient.id}`" class="text-indigo-600 hover:text-indigo-900 mr-3">View</Link>
                                <Link :href="`/patients/${patient.id}/edit`" class="text-yellow-600 hover:text-yellow-900">Edit</Link>
                            </td>
                        </tr>
                        <tr v-if="!patients.data || patients.data.length === 0">
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No patients found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="patients.total > patients.per_page" class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ patients.from }} to {{ patients.to }} of {{ patients.total }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    patients: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
let timeout;

const debouncedSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/patients', { search: search.value }, { preserveState: true, replace: true });
    }, 500);
};

const calculateAge = (dob) => {
    if (!dob) return '-';
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age + ' years';
};
</script>