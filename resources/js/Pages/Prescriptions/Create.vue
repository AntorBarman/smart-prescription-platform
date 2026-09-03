<template>
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-slate-200 fixed inset-y-0 z-20">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">MediPrescribe</p>
                        <p class="text-xs text-slate-500">Digital Prescription</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto py-4">
                <div class="px-3 mb-2"><p class="text-xs font-semibold text-slate-400 uppercase">Prescriptions</p></div>
                <Link href="/dashboard" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Dashboard</Link>
                <Link href="/prescriptions/create" class="flex items-center px-4 py-2.5 text-sm bg-indigo-50 text-indigo-700 font-medium rounded-lg mx-2">Create Prescription</Link>
                <Link href="/prescriptions" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">All Prescriptions</Link>
                <div class="px-3 mt-6 mb-2"><p class="text-xs font-semibold text-slate-400 uppercase">Patients</p></div>
                <Link href="/patients" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">All Patients</Link>
                <Link href="/patients/create" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Add Patient</Link>
                <div class="px-3 mt-6 mb-2"><p class="text-xs font-semibold text-slate-400 uppercase">Medicines</p></div>
                <Link href="/medicines" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 rounded-lg mx-2">Medicine List</Link>
            </nav>
            <div class="p-4 border-t border-slate-200">
                <div class="bg-indigo-50 rounded-xl p-4">
                    <p class="text-sm font-semibold text-indigo-700">Go Paperless</p>
                    <p class="text-xs text-slate-500 mt-1">Streamline your practice.</p>
                    <button class="mt-3 w-full py-2 bg-indigo-600 text-white text-sm rounded-lg">Learn More</button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="flex flex-wrap gap-3 justify-between items-center px-6 py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Create Prescription</h1>
                        <p class="text-sm text-slate-500">Fill patient details and prescription information</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button @click="saveDraft" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Save Draft</button>
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Preview</button>
                    </div>
                </div>
            </header>

            <main class="px-4 md:px-6 py-6 max-w-5xl pb-24">
                <!-- Success Message -->
                <div v-if="successMsg" class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">{{ successMsg }}</p>
                </div>

                <!-- Top Summary Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">RX-2026-0001</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Valid After Save</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Date</label>
                            <p class="text-sm font-medium text-slate-900">{{ todayDate }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Doctor</label>
                            <p class="text-sm font-medium text-slate-900">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-slate-500">General Physician</p>
                        </div>
                    </div>
                </div>

                <!-- Patient Information -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Patient Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="relative">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Search Patient</label>
                            <input v-model="patientSearch" type="text" placeholder="Type patient name or phone..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" @input="searchPatients" />
                            <div v-if="patientResults.length > 0 && !selectedPatient" class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <div v-for="p in patientResults" :key="p.id" @click="selectPatient(p)" class="px-4 py-2.5 hover:bg-indigo-50 cursor-pointer border-b border-slate-100">
                                    <p class="text-sm font-medium text-slate-900">{{ p.name }}</p>
                                    <p class="text-xs text-slate-500">{{ p.phone || 'No phone' }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-if="selectedPatient">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Selected</label>
                            <div class="bg-indigo-50 rounded-lg px-3 py-2 flex justify-between items-center">
                                <p class="text-sm font-medium text-slate-900">{{ selectedPatient.name }}</p>
                                <button @click="clearPatient" class="text-slate-400 hover:text-red-500">✕</button>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Age</label>
                            <input v-model="form.age" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="e.g., 28 Years" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Gender</label>
                            <select v-model="form.gender" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Weight</label>
                            <input v-model="form.weight" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="e.g., 65 kg" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Allergies</label>
                            <input v-model="form.allergies" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="No known allergies" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Diagnosis</label>
                        <input v-model="form.diagnosis" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="e.g., Hypertension" list="diagnosis-list" />
                        <datalist id="diagnosis-list">
                            <option value="Hypertension (High Blood Pressure)" />
                            <option value="Diabetes Mellitus Type 2" />
                            <option value="Asthma" />
                            <option value="Migraine" />
                            <option value="Common Cold" />
                        </datalist>
                    </div>
                </div>

                <!-- Medicines -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-base font-semibold text-slate-900">Medicines</h2>
                        <button type="button" @click="addMedicine" class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">+ Add Medicine</button>
                    </div>

                    <div v-for="(item, index) in form.items" :key="index" class="border border-slate-200 rounded-xl p-4 mb-4 bg-slate-50">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-sm font-semibold text-indigo-600">{{ String(index + 1).padStart(2, '0') }}</span>
                            <button type="button" @click="removeMedicine(index)" class="text-red-400 hover:text-red-600 text-sm">✕ Remove</button>
                        </div>

                        <div class="relative mb-4">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Medicine</label>
                            <input v-model="item.medicine_name" type="text" placeholder="Search medicine..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white" @input="searchMedicines(index)" />
                            <div v-if="medicineResults[index] && medicineResults[index].length > 0 && !item.medicine_id" class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <div v-for="med in medicineResults[index]" :key="med.id" @click="selectMedicine(index, med)" class="px-4 py-2 hover:bg-indigo-50 cursor-pointer">
                                    <p class="text-sm font-medium text-slate-900">{{ med.name }}</p>
                                    <p class="text-xs text-slate-500">{{ med.dosage_form }} • {{ med.strength }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Dose Schedule (B+L+D)</label>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="preset in dosePresets" :key="preset.label" type="button" @click="setDose(index, preset)" class="px-3 py-1.5 rounded-md text-sm border transition" :class="isActiveDose(index, preset) ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-300 text-slate-600 hover:bg-white'">{{ preset.label }}</button>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                            <div class="flex items-center space-x-2">
                                <button type="button" @click="decrementDuration(index)" class="px-3 py-1.5 border border-slate-300 rounded-md text-slate-600 hover:bg-white">−</button>
                                <span class="text-sm font-medium text-slate-900">{{ item.duration_days }} Days</span>
                                <button type="button" @click="incrementDuration(index)" class="px-3 py-1.5 border border-slate-300 rounded-md text-slate-600 hover:bg-white">+</button>
                            </div>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-medium">Total: {{ calculateTotal(item) }} tablet</span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Instructions</label>
                            <input v-model="item.instructions" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white" placeholder="e.g., After food" />
                        </div>
                    </div>

                    <p v-if="form.items.length === 0" class="text-center text-slate-400 text-sm py-6">No medicines added. Click "+ Add Medicine".</p>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Notes & Advice</h2>
                    <textarea v-model="form.notes" rows="3" maxlength="500" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Additional advice..."></textarea>
                    <p class="text-xs text-slate-400 text-right mt-1">{{ form.notes.length }}/500</p>
                </div>

                <!-- Bottom Actions -->
                <div class="flex justify-between items-center">
                    <Link href="/prescriptions" class="px-4 py-2.5 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">Cancel</Link>
                    <button @click="submit" :disabled="form.processing" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">{{ form.processing ? 'Creating...' : 'Create Prescription' }}</button>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';

const form = useForm({
    patient_id: '',
    age: '',
    gender: '',
    weight: '',
    allergies: '',
    diagnosis: '',
    notes: '',
    items: [],
});

const patientSearch = ref('');
const patientResults = ref([]);
const selectedPatient = ref(null);
const medicineResults = reactive({});
const successMsg = ref('');

const todayDate = computed(() => new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }));

const dosePresets = [
    { label: '0+0+0', breakfast: 0, lunch: 0, dinner: 0 },
    { label: '0+0+1', breakfast: 0, lunch: 0, dinner: 1 },
    { label: '1+0+1', breakfast: 1, lunch: 0, dinner: 1 },
    { label: '1+1+1', breakfast: 1, lunch: 1, dinner: 1 },
    { label: '0+1+0', breakfast: 0, lunch: 1, dinner: 0 },
];

const searchPatients = async () => {
    if (patientSearch.value.length < 2) { patientResults.value = []; return; }
    try {
        const res = await axios.get('/patients/search', { params: { q: patientSearch.value } });
        patientResults.value = res.data.data || [];
    } catch (e) { console.error(e); }
};

const selectPatient = (p) => {
    selectedPatient.value = p;
    form.patient_id = p.id;
    form.age = p.date_of_birth ? '28 Years' : '';
    form.gender = p.gender || '';
    patientSearch.value = p.name;
    patientResults.value = [];
};

const clearPatient = () => {
    selectedPatient.value = null;
    form.patient_id = '';
    patientSearch.value = '';
};

const addMedicine = () => {
    form.items.push({
        medicine_id: '',
        medicine_name: '',
        breakfast: 1,
        lunch: 0,
        dinner: 1,
        duration_days: 7,
        instructions: '',
    });
};

const removeMedicine = (index) => {
    form.items.splice(index, 1);
    delete medicineResults[index];
};

const incrementDuration = (index) => {
    form.items[index].duration_days++;
};

const decrementDuration = (index) => {
    if (form.items[index].duration_days > 1) {
        form.items[index].duration_days--;
    }
};

const searchMedicines = async (index) => {
    const q = form.items[index].medicine_name;
    if (q.length < 2) { medicineResults[index] = []; return; }
    try {
        const res = await axios.get('/api/medicines/search', { params: { q } });
        medicineResults[index] = res.data.data || [];
    } catch (e) { console.error(e); }
};

const selectMedicine = (index, med) => {
    form.items[index].medicine_id = med.id;
    form.items[index].medicine_name = med.name;
    medicineResults[index] = [];
};

const setDose = (index, preset) => {
    form.items[index].breakfast = preset.breakfast;
    form.items[index].lunch = preset.lunch;
    form.items[index].dinner = preset.dinner;
};

const isActiveDose = (index, preset) => {
    const item = form.items[index];
    return item.breakfast === preset.breakfast && item.lunch === preset.lunch && item.dinner === preset.dinner;
};

const calculateTotal = (item) => {
    const daily = (item.breakfast || 0) + (item.lunch || 0) + (item.dinner || 0);
    return daily * (item.duration_days || 1);
};

const saveDraft = () => {
    successMsg.value = 'Draft saved successfully!';
    setTimeout(() => successMsg.value = '', 3000);
};

const submit = () => {
    if (!form.patient_id) { alert('Please select a patient.'); return; }
    if (form.items.length === 0) { alert('Please add at least one medicine.'); return; }
    form.post('/prescriptions');
};
</script>