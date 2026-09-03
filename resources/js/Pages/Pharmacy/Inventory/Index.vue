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
                        <p class="text-xs text-slate-500">Pharmacy Panel</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 py-4">
                <Link href="/pharmacy/dashboard" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Dashboard</Link>
                <Link href="/pharmacy/inventory" class="flex items-center px-4 py-2.5 text-sm bg-indigo-50 text-indigo-700 font-medium rounded-lg mx-2">Inventory</Link>
                <Link href="/pharmacy/scanner" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">QR Scanner</Link>
                <Link href="/pharmacy/sales" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Sales</Link>
                <Link href="/prescriptions" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Prescriptions</Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="flex flex-wrap gap-3 justify-between items-center px-6 py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Inventory Management</h1>
                        <p class="text-sm text-slate-500">Manage your pharmacy medicine stock</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button @click="showBulkImport = true" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                            📥 Bulk Import
                        </button>
                        <button @click="showAddModal = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                            + Add Stock
                        </button>
                        <form @submit.prevent="logout">
                            <button type="submit" class="px-3 py-1.5 text-xs text-slate-500 border border-slate-300 rounded-md">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-6 py-6">
                <!-- Success Message -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">{{ $page.props.flash.success }}</p>
                </div>

                <!-- Search -->
                <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
                    <input v-model="search" type="text" placeholder="Search medicines..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" @input="debouncedSearch" />
                </div>

                <!-- Inventory Table -->
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="item in inventory.data" :key="item.id" class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-slate-900">{{ item.medicine?.name }}</p>
                                    <p class="text-xs text-slate-500">{{ item.medicine?.strength }} • {{ item.medicine?.dosage_form }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ item.medicine?.category?.name || '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold" :class="item.stock_quantity <= item.reorder_level ? 'text-red-600' : 'text-slate-900'">
                                        {{ item.stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900">৳{{ item.selling_price }}</td>
                                <td class="px-6 py-4">
                                    <span v-if="item.stock_quantity <= item.reorder_level" class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Low Stock</span>
                                    <span v-else-if="item.stock_quantity === 0" class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Out of Stock</span>
                                    <span v-else class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">In Stock</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="openAdjustModal(item)" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Adjust</button>
                                </td>
                            </tr>
                            <tr v-if="!inventory.data || inventory.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-400">
                                    No inventory items found. Use <strong>Bulk Import</strong> to add all stock at once.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <!-- Add Stock Modal -->
        <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Add Stock</h3>
                <form @submit.prevent="addStock">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Medicine</label>
                            <select v-model="addForm.medicine_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                                <option value="">Select Medicine</option>
                                <option v-for="med in allMedicines" :key="med.id" :value="med.id">{{ med.name }} ({{ med.strength }})</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Quantity</label>
                            <input v-model.number="addForm.quantity" type="number" min="1" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Selling Price (৳)</label>
                            <input v-model.number="addForm.selling_price" type="number" min="0" step="0.01" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Import Modal -->
        <div v-if="showBulkImport" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg">
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Bulk Import Stock</h3>
                <p class="text-sm text-slate-500 mb-4">Upload CSV file to add all your pharmacy stock at once</p>
                
                <form @submit.prevent="bulkImport">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">CSV File</label>
                        <input type="file" @change="handleFileUpload" accept=".csv,.txt" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required />
                    </div>
                    
                    <div class="mb-4 bg-slate-50 rounded-lg p-3">
                        <p class="text-xs font-semibold text-slate-700 mb-2">CSV Format:</p>
                        <pre class="text-xs text-slate-600">Medicine Name, Stock Quantity, Selling Price
Paracetamol 500mg, 100, 2.50
Amoxicillin 500mg, 50, 15.00
Cetirizine 10mg, 200, 5.00</pre>
                    </div>

                    <button type="button" @click="downloadSampleCSV" class="text-xs text-indigo-600 hover:text-indigo-700 mb-4">
                        📄 Download Sample CSV
                    </button>

                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" @click="showBulkImport = false" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">Cancel</button>
                        <button type="submit" :disabled="!selectedFile" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm disabled:opacity-50">Import Stock</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Adjust Stock Modal -->
        <div v-if="showAdjustModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Adjust Stock - {{ selectedItem?.medicine?.name }}</h3>
                <form @submit.prevent="adjustStock">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Type</label>
                            <select v-model="adjustForm.type" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                                <option value="ADJUSTMENT">Adjustment</option>
                                <option value="DAMAGE">Damage</option>
                                <option value="EXPIRED">Expired</option>
                                <option value="RETURN">Return</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Quantity (+/-)</label>
                            <input v-model.number="adjustForm.quantity" type="number" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showAdjustModal = false" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Adjust</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    inventory: Object,
    pharmacy: Object,
    allMedicines: { type: Array, default: () => [] },
});

const search = ref('');
const showAddModal = ref(false);
const showBulkImport = ref(false);
const showAdjustModal = ref(false);
const selectedItem = ref(null);
const selectedFile = ref(null);

const addForm = reactive({
    medicine_id: '',
    quantity: 1,
    selling_price: 0,
});

const adjustForm = reactive({
    quantity: 0,
    type: 'ADJUSTMENT',
});

let timeout;

const debouncedSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/pharmacy/inventory', { search: search.value }, { preserveState: true, replace: true });
    }, 500);
};

const addStock = () => {
    router.post('/pharmacy/inventory/add-stock', addForm, {
        onSuccess: () => {
            showAddModal.value = false;
            addForm.medicine_id = '';
            addForm.quantity = 1;
            addForm.selling_price = 0;
        },
    });
};

const handleFileUpload = (event) => {
    selectedFile.value = event.target.files[0];
};

const bulkImport = () => {
    if (!selectedFile.value) return;
    
    const formData = new FormData();
    formData.append('file', selectedFile.value);
    
    router.post('/pharmacy/inventory/bulk-import', formData, {
        onSuccess: () => {
            showBulkImport.value = false;
            selectedFile.value = null;
        },
    });
};

const downloadSampleCSV = () => {
    const csvContent = "Medicine Name,Stock Quantity,Selling Price\nParacetamol 500mg,100,2.50\nAmoxicillin 500mg,50,15.00\nCetirizine 10mg,200,5.00\nAmlodipine 5mg,150,8.00\nMetformin 500mg,300,6.00\nOmeprazole 20mg,250,10.00\nLoratadine 10mg,180,7.50\nAtorvastatin 20mg,120,20.00";
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sample_inventory.csv';
    a.click();
    URL.revokeObjectURL(url);
};

const openAdjustModal = (item) => {
    selectedItem.value = item;
    adjustForm.quantity = 0;
    showAdjustModal.value = true;
};

const adjustStock = () => {
    router.post(`/pharmacy/inventory/${selectedItem.value.id}/adjust`, adjustForm, {
        onSuccess: () => {
            showAdjustModal.value = false;
        },
    });
};

const logout = () => {
    router.post('/logout');
};
</script>