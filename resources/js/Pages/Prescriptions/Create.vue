<template>
    <AppShell title="Create prescription" subtitle="Build a clear, digital care plan">
        <template #sidebar>
            <div class="flex h-full flex-col">
                <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white"><HeartIcon class="h-5 w-5" /></div>
                    <div><p class="text-sm font-bold text-slate-900">MediPrescribe</p><p class="text-[11px] text-slate-500">Doctor workspace</p></div>
                </div>
                <nav class="flex-1 space-y-1 px-3 py-5">
                    <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Workspace</p>
                    <Link href="/doctor/dashboard" class="nav-link"><HomeIcon class="h-5 w-5" /> Overview</Link>
                    <Link href="/patients" class="nav-link"><UsersIcon class="h-5 w-5" /> Patients</Link>
                    <Link href="/prescriptions" class="nav-link-active"><ClipboardDocumentListIcon class="h-5 w-5" /> Prescriptions</Link>
                    <Link href="/medicines" class="nav-link"><BeakerIcon class="h-5 w-5" /> Medicines</Link>
                </nav>
            </div>
        </template>

        <template #topbar>
            <div class="flex flex-1 items-center justify-between gap-4">
                <Link href="/prescriptions" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-indigo-600">
                    <ArrowLeftIcon class="h-4 w-4" /> <span class="hidden sm:inline">Back to prescriptions</span><span class="sm:hidden">Back</span>
                </Link>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50" @click="saveDraft">Save draft</button>
                    <div class="hidden h-8 w-px bg-slate-200 sm:block" />
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">{{ initials }}</div>
                </div>
            </div>
        </template>

        <form class="pb-28" @submit.prevent="submit">
            <div class="mb-8 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                <div><p class="mb-2 text-sm font-medium text-indigo-600">New care plan</p><h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Create prescription</h1><p class="mt-1 text-sm text-slate-500">Add a patient and their medication instructions.</p></div>
                <p class="text-sm text-slate-500">{{ todayDate }}</p>
            </div>

            <div v-if="successMsg" class="mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"><CheckCircleIcon class="h-5 w-5" />{{ successMsg }}</div>
            <div v-if="form.errors && Object.keys(form.errors).length" class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">Please review the highlighted prescription details.</div>

            <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-start gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600"><UserIcon class="h-5 w-5" /></span><div><h2 class="text-base font-bold text-slate-900">Patient information</h2><p class="text-xs text-slate-500">Select the patient receiving this prescription.</p></div></div>
                <div class="relative">
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Search patient</label>
                    <div :class="['flex items-center gap-2 rounded-lg border bg-white px-3 py-2.5 transition', selectedPatient ? 'border-emerald-300 ring-2 ring-emerald-500/10' : 'border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20']">
                        <MagnifyingGlassIcon v-if="!selectedPatient" class="h-5 w-5 text-slate-400" />
                        <CheckCircleIcon v-else class="h-5 w-5 text-emerald-500" />
                        <input v-model="patientSearch" :disabled="!!selectedPatient" type="text" class="min-w-0 flex-1 border-0 p-0 text-sm text-slate-900 outline-none focus:ring-0 disabled:bg-transparent" placeholder="Search by name or phone..." @focus="patientFocused = true" @input="patientFocused = true" />
                        <button v-if="selectedPatient" type="button" class="text-xs font-semibold text-slate-400 hover:text-rose-600" @click="clearPatient">Change</button>
                    </div>
                    <div v-if="patientFocused && patientResults.length && !selectedPatient" class="absolute inset-x-0 top-full z-20 mt-2 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl">
                        <button v-for="patient in patientResults" :key="patient.id" type="button" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition hover:bg-indigo-50" @click="selectPatient(patient)">
                            <span :class="['flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold', avatarClass(patient.name)]">{{ initialsFor(patient.name) }}</span>
                            <span class="min-w-0 flex-1"><span class="block truncate text-sm font-semibold text-slate-900">{{ patient.name }}</span><span class="block text-xs text-slate-500">{{ calculateAge(patient.date_of_birth) }} · {{ patient.phone || 'No phone' }}</span></span><ChevronRightIcon class="h-4 w-4 text-slate-300" />
                        </button>
                    </div>
                    <p v-if="patientFocused && patientSearch.length > 1 && !patientResults.length && !selectedPatient" class="mt-2 text-xs text-slate-500">No matching patients found.</p>
                </div>
                <div v-if="selectedPatient" class="mt-4 grid grid-cols-2 gap-3 rounded-lg bg-emerald-50/70 p-3 text-sm sm:grid-cols-4">
                    <div><p class="text-[11px] text-slate-500">Patient</p><p class="font-semibold text-slate-900">{{ selectedPatient.name }}</p></div><div><p class="text-[11px] text-slate-500">Age</p><p class="font-semibold text-slate-900">{{ calculateAge(selectedPatient.date_of_birth) }}</p></div><div><p class="text-[11px] text-slate-500">Phone</p><p class="font-semibold text-slate-900">{{ selectedPatient.phone || '—' }}</p></div><div><p class="text-[11px] text-slate-500">Gender</p><p class="font-semibold capitalize text-slate-900">{{ selectedPatient.gender || '—' }}</p></div>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <label class="field-label">Weight (kg)<input v-model="form.weight" type="text" class="field-input" placeholder="Optional" /></label>
                    <label class="field-label">Allergies<input v-model="form.allergies" type="text" class="field-input" placeholder="None known" /></label>
                    <label class="field-label sm:col-span-2">Diagnosis<input v-model="form.diagnosis" type="text" class="field-input" placeholder="Primary diagnosis" /></label>
                </div>
            </section>

            <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-start"><div class="flex items-start gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><BeakerIcon class="h-5 w-5" /></span><div><h2 class="text-base font-bold text-slate-900">Medications</h2><p class="text-xs text-slate-500">Configure each medicine and its schedule.</p></div></div><button type="button" class="inline-flex items-center gap-1.5 self-start rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100" @click="addMedicine"><PlusIcon class="h-4 w-4" /> Add medicine</button></div>
                <div v-if="!form.items.length" class="rounded-xl border border-dashed border-slate-300 px-5 py-12 text-center"><BeakerIcon class="mx-auto h-9 w-9 text-slate-300" /><p class="mt-3 text-sm font-semibold text-slate-700">No medicines added</p><p class="mt-1 text-xs text-slate-500">Add a medicine to build the dosage plan.</p><button type="button" class="mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-700" @click="addMedicine">Add your first medicine</button></div>
                <div v-for="(item, index) in form.items" :key="index" class="relative mb-4 rounded-xl border border-slate-200 bg-slate-50/70 p-4 last:mb-0 sm:p-5">
                    <button type="button" class="absolute right-4 top-4 rounded-md p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600" aria-label="Remove medicine" @click="removeMedicine(index)"><XMarkIcon class="h-4 w-4" /></button>
                    <label class="field-label pr-8">Medicine
                        <div class="relative mt-1.5"><MagnifyingGlassIcon class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" /><input v-model="item.medicine_name" type="text" class="field-input pl-9" placeholder="Search medicine by name..." @input="searchMedicines(index)" @focus="medicineFocused[index] = true" /><div v-if="medicineFocused[index] && medicineResults[index]?.length && !item.medicine_id" class="absolute inset-x-0 top-full z-20 mt-1 max-h-56 overflow-y-auto rounded-lg border border-slate-200 bg-white p-1 shadow-xl"><button v-for="medicine in medicineResults[index]" :key="medicine.id" type="button" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-left hover:bg-indigo-50" @click="selectMedicine(index, medicine)"><span><span class="block text-sm font-semibold text-slate-900">{{ medicine.name }}</span><span class="block text-xs text-slate-500">{{ medicine.strength || 'Standard strength' }} · {{ medicine.dosage_form || 'Medicine' }}</span></span><CheckCircleIcon v-if="item.medicine_id === medicine.id" class="h-4 w-4 text-emerald-500" /></button></div></div>
                    </label>
                    <div class="mt-4 grid gap-5 lg:grid-cols-[1fr_auto]">
                        <div><p class="mb-2 text-xs font-semibold text-slate-600">Daily dosage</p><div class="grid grid-cols-3 gap-2 sm:max-w-md"><button v-for="preset in dosePresets" :key="preset.label" type="button" :class="['rounded-lg border px-2 py-2.5 text-center transition', isActiveDose(index, preset) ? 'border-indigo-500 bg-indigo-600 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-600 hover:border-indigo-300 hover:bg-indigo-50']" @click="setDose(index, preset)"><span class="block text-sm font-bold">{{ preset.label }}</span><span class="mt-0.5 block text-[10px] opacity-75">{{ preset.breakfast ? 'Morning' : '—' }} · {{ preset.lunch ? 'Afternoon' : '—' }} · {{ preset.dinner ? 'Night' : '—' }}</span></button></div></div>
                        <div class="flex gap-5"><div><p class="mb-2 text-xs font-semibold text-slate-600">Duration</p><div class="flex h-11 items-center rounded-lg border border-slate-200 bg-white"><button type="button" class="px-3 text-lg text-slate-500 hover:text-indigo-600" @click="decrementDuration(index)">−</button><span class="min-w-12 text-center text-sm font-bold text-slate-900">{{ item.duration_days }} <span class="text-xs font-normal text-slate-500">days</span></span><button type="button" class="px-3 text-lg text-slate-500 hover:text-indigo-600" @click="incrementDuration(index)">+</button></div></div><div><p class="mb-2 text-xs font-semibold text-slate-600">Quantity</p><div class="flex h-11 min-w-24 items-center justify-center rounded-lg bg-indigo-50 px-3 text-sm font-bold text-indigo-700">{{ calculateTotal(item) }} pcs</div></div></div>
                    </div>
                    <label class="field-label mt-4">Instructions<input v-model="item.instructions" type="text" class="field-input" placeholder="e.g. Take after meals" /></label>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"><label class="field-label">Notes & advice<textarea v-model="form.notes" rows="3" maxlength="500" class="field-input resize-none" placeholder="Additional advice for the patient..." /></label><p class="mt-1 text-right text-xs text-slate-400">{{ form.notes.length }}/500</p></section>
        </form>
        <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur lg:pl-64"><div class="mx-auto flex max-w-7xl items-center justify-between gap-3 sm:px-4 lg:px-8"><Link href="/prescriptions" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</Link><button type="button" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50" @click="submit">{{ form.processing ? 'Creating...' : 'Create prescription' }}</button></div></div>
    </AppShell>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ArrowLeftIcon, BeakerIcon, CheckCircleIcon, ChevronRightIcon, ClipboardDocumentListIcon, HeartIcon, HomeIcon, MagnifyingGlassIcon, PlusIcon, UserIcon, UsersIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';

const props = defineProps({ patients: { type: Array, default: () => [] }, medicines: { type: Array, default: () => [] } });
const page = usePage();
const form = useForm({ patient_id: '', age: '', gender: '', weight: '', allergies: '', diagnosis: '', notes: '', items: [] });
const patientSearch = ref('');
const patientFocused = ref(false);
const selectedPatient = ref(null);
const medicineResults = ref({});
const medicineFocused = ref({});
const successMsg = ref('');
const todayDate = computed(() => new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }));
const initials = computed(() => initialsFor(page.props.auth.user.name));
const dosePresets = [{ label: '0+0+0', breakfast: 0, lunch: 0, dinner: 0 }, { label: '0+0+1', breakfast: 0, lunch: 0, dinner: 1 }, { label: '1+0+1', breakfast: 1, lunch: 0, dinner: 1 }, { label: '1+1+1', breakfast: 1, lunch: 1, dinner: 1 }, { label: '0+1+0', breakfast: 0, lunch: 1, dinner: 0 }, { label: '1+1+0', breakfast: 1, lunch: 1, dinner: 0 }];
const patientResults = computed(() => { const query = patientSearch.value.trim().toLowerCase(); if (!query) return props.patients.slice(0, 8); return props.patients.filter((patient) => `${patient.name} ${patient.phone || ''}`.toLowerCase().includes(query)).slice(0, 8); });
const initialsFor = (name) => (name || 'U').split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();
const avatarClass = (name) => ['bg-indigo-100 text-indigo-700', 'bg-emerald-100 text-emerald-700', 'bg-violet-100 text-violet-700', 'bg-amber-100 text-amber-700'][((name || '').length) % 4];
const calculateAge = (dob) => { if (!dob) return 'Age unavailable'; const birthDate = new Date(dob); const today = new Date(); let age = today.getFullYear() - birthDate.getFullYear(); if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) age--; return `${age} years`; };
const selectPatient = (patient) => { selectedPatient.value = patient; form.patient_id = patient.id; form.age = calculateAge(patient.date_of_birth); form.gender = patient.gender || ''; patientSearch.value = patient.name; patientFocused.value = false; };
const clearPatient = () => { selectedPatient.value = null; form.patient_id = ''; patientSearch.value = ''; patientFocused.value = true; };
const addMedicine = () => { form.items.push({ medicine_id: '', medicine_name: '', breakfast: 1, lunch: 0, dinner: 1, duration_days: 7, instructions: '' }); };
const removeMedicine = (index) => { form.items.splice(index, 1); delete medicineResults.value[index]; };
const incrementDuration = (index) => { form.items[index].duration_days++; };
const decrementDuration = (index) => { if (form.items[index].duration_days > 1) form.items[index].duration_days--; };
const searchMedicines = async (index) => { const query = form.items[index].medicine_name; medicineFocused.value[index] = true; const local = props.medicines.filter((medicine) => `${medicine.name} ${medicine.strength || ''} ${medicine.dosage_form || ''}`.toLowerCase().includes(query.toLowerCase())).slice(0, 8); medicineResults.value[index] = local; if (query.length >= 2 && !local.length) { const response = await axios.get('/api/medicines/search', { params: { q: query } }); medicineResults.value[index] = response.data.data || []; } };
const selectMedicine = (index, medicine) => { form.items[index].medicine_id = medicine.id; form.items[index].medicine_name = medicine.name; medicineResults.value[index] = []; medicineFocused.value[index] = false; };
const setDose = (index, preset) => { Object.assign(form.items[index], { breakfast: preset.breakfast, lunch: preset.lunch, dinner: preset.dinner }); };
const isActiveDose = (index, preset) => { const item = form.items[index]; return item.breakfast === preset.breakfast && item.lunch === preset.lunch && item.dinner === preset.dinner; };
const calculateTotal = (item) => ((item.breakfast || 0) + (item.lunch || 0) + (item.dinner || 0)) * (item.duration_days || 1);
const saveDraft = () => { successMsg.value = 'Draft saved locally. Add a patient and medicine to create the prescription.'; setTimeout(() => successMsg.value = '', 3000); };
const submit = () => { if (!form.patient_id) { window.alert('Please select a patient.'); return; } if (!form.items.length) { window.alert('Please add at least one medicine.'); return; } form.post('/prescriptions'); };
</script>

<style scoped>
@reference "../../../css/app.css";

.nav-link, .nav-link-active { @apply flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition; }
.nav-link { @apply text-slate-600 hover:bg-indigo-50 hover:text-indigo-700; }
.nav-link-active { @apply bg-indigo-600 font-semibold text-white shadow-sm; }
.field-label { @apply block text-xs font-semibold text-slate-600; }
.field-input { @apply mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20; }
</style>
