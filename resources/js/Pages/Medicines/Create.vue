<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <Link href="/medicines" class="text-indigo-600 hover:text-indigo-900 mr-4">&larr; Back</Link>
                    <h1 class="text-2xl font-semibold text-gray-900">Add Medicine</h1>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Medicine Name *</label>
                            <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Generic *</label>
                            <select v-model="form.generic_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Generic</option>
                                <option v-for="g in generics" :key="g.id" :value="g.id">{{ g.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category *</label>
                            <select v-model="form.category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Category</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Strength *</label>
                            <input v-model="form.strength" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dosage Form *</label>
                            <select v-model="form.dosage_form" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="syrup">Syrup</option>
                                <option value="injection">Injection</option>
                                <option value="cream">Cream</option>
                                <option value="drops">Drops</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU *</label>
                            <input v-model="form.sku" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <Link href="/medicines" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs text-gray-700 uppercase">Cancel</Link>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs text-white uppercase">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

defineProps({
    categories: Array,
    generics: Array,
});

const form = useForm({
    name: '',
    category_id: '',
    generic_id: '',
    strength: '',
    dosage_form: '',
    sku: '',
    barcode: '',
    description: '',
    requires_prescription: true,
    is_active: true,
});

const submit = () => {
    form.post('/medicines');
};
</script>