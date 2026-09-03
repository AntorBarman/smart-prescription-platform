<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">Medicines</h1>
                    <Link href="/medicines/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Add Medicine</Link>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Search Box -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input v-model="search" type="text" placeholder="Search medicines..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" @input="debouncedSearch" />
                    </div>
                    <select v-model="category" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" @change="filter">
                        <option value="">All Categories</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Generic</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Strength</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Form</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="medicine in medicines.data" :key="medicine.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ medicine.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ medicine.generic ? medicine.generic.name : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ medicine.category ? medicine.category.name : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ medicine.strength }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ medicine.dosage_form }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ medicine.sku }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Link :href="`/medicines/${medicine.id}`" class="text-indigo-600 hover:text-indigo-900 mr-3">View</Link>
                                <Link :href="`/medicines/${medicine.id}/edit`" class="text-yellow-600 hover:text-yellow-900">Edit</Link>
                            </td>
                        </tr>
                        <tr v-if="!medicines.data || medicines.data.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No medicines found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="medicines.total > medicines.per_page" class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ medicines.from }} to {{ medicines.to }} of {{ medicines.total }}
                </div>
                <div class="flex space-x-1">
                    <button v-for="link in medicines.links" :key="link.label" :disabled="!link.url || link.active" @click="goToPage(link.url)" class="px-3 py-1 rounded text-sm" :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'" v-html="link.label"></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    medicines: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
let timeout;

const debouncedSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        filter();
    }, 500);
};

const filter = () => {
    router.get('/medicines', {
        search: search.value,
        category: category.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const goToPage = (url) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true });
    }
};
</script>