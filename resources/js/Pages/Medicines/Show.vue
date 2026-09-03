<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <Link href="/medicines" class="text-indigo-600 hover:text-indigo-900 mr-4">&larr; Back</Link>
                    <h1 class="text-2xl font-semibold text-gray-900">Medicine Details</h1>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">{{ medicine.name }}</h2>
                    <p class="text-sm text-gray-500">{{ medicine.generic?.name }}</p>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.category?.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Generic</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.generic?.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Strength</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.strength }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dosage Form</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.dosage_form }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">SKU</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.sku }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.barcode || '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prescription Required</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span v-if="medicine.requires_prescription" class="text-red-600">Yes</span>
                                <span v-else class="text-green-600">No</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm">
                                <span v-if="medicine.is_active" class="text-green-600">Active</span>
                                <span v-else class="text-red-600">Inactive</span>
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ medicine.description || '-' }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <Link href="/medicines" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs text-gray-700 uppercase hover:bg-gray-50">Back</Link>
                    <Link :href="`/medicines/${medicine.id}/edit`" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md text-xs text-white uppercase hover:bg-yellow-700">Edit</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    medicine: Object,
});
</script>