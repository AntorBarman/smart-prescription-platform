<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <Link href="/medicines" class="text-indigo-600 hover:text-indigo-900 mr-4">&larr; Back</Link>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Medicine</h1>
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
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Generic *</label>
                            <select v-model="form.generic_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Generic</option>
                                <option v-for="g in generics" :key="g.id" :value="g.id">{{ g.name }}</option>
                            </select>
                            <p v-if="form.errors.generic_id" class="mt-2 text-sm text-red-600">{{ form.errors.generic_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category *</label>
                            <select v-model="form.category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Category</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                            </select>
                            <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">{{ form.errors.category_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Strength *</label>
                            <input v-model="form.strength" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            <p v-if="form.errors.strength" class="mt-2 text-sm text-red-600">{{ form.errors.strength }}</p>
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
                                <option value="ointment">Ointment</option>
                                <option value="drops">Drops</option>
                                <option value="inhaler">Inhaler</option>
                                <option value="spray">Spray</option>
                            </select>
                            <p v-if="form.errors.dosage_form" class="mt-2 text-sm text-red-600">{{ form.errors.dosage_form }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU *</label>
                            <input v-model="form.sku" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            <p v-if="form.errors.sku" class="mt-2 text-sm text-red-600">{{ form.errors.sku }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Barcode</label>
                            <input v-model="form.barcode" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2 flex items-center space-x-6">
                            <label class="flex items-center">
                                <input v-model="form.requires_prescription" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                <span class="ml-2 text-sm text-gray-700">Requires Prescription</span>
                            </label>
                            <label class="flex items-center">
                                <input v-model="form.is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <Link href="/medicines" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs text-gray-700 uppercase hover:bg-gray-50">Cancel</Link>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs text-white uppercase hover:bg-indigo-700 disabled:opacity-50">Update Medicine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    medicine: Object,
    categories: Array,
    generics: Array,
});

const form = useForm({
    name: props.medicine.name,
    category_id: props.medicine.category_id,
    generic_id: props.medicine.generic_id,
    strength: props.medicine.strength,
    dosage_form: props.medicine.dosage_form,
    sku: props.medicine.sku,
    barcode: props.medicine.barcode || '',
    description: props.medicine.description || '',
    requires_prescription: props.medicine.requires_prescription,
    is_active: props.medicine.is_active,
});

const submit = () => {
    form.put(`/medicines/${props.medicine.id}`);
};
</script>