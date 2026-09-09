<template>
    <AppShell title="Edit patient" subtitle="Update patient information">
        <div class="mb-6 flex items-center justify-between"><Link href="/patients" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600"><ArrowLeftIcon class="h-4 w-4" /> Back to patients</Link></div>
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="grid gap-5 sm:grid-cols-2" @submit.prevent="submit">
                <label class="field-label sm:col-span-2">Full name<input v-model="form.name" class="field-input" required /><span v-if="form.errors.name" class="error">{{ form.errors.name }}</span></label>
                <label class="field-label">Date of birth<input v-model="form.date_of_birth" type="date" class="field-input" /></label>
                <label class="field-label">Gender<select v-model="form.gender" class="field-input"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></label>
                <label class="field-label">Blood group<select v-model="form.blood_group" class="field-input"><option value="">Select</option><option v-for="group in bloodGroups" :key="group" :value="group">{{ group }}</option></select></label>
                <label class="field-label">Phone<input v-model="form.phone" type="tel" class="field-input" /></label>
                <label class="field-label">Email<input v-model="form.email" type="email" class="field-input" /></label>
                <label class="field-label sm:col-span-2">Address<textarea v-model="form.address" rows="2" class="field-input" /></label>
                <label class="field-label">Allergies<textarea v-model="form.allergies" rows="3" class="field-input" /></label>
                <label class="field-label">Medical history<textarea v-model="form.medical_history" rows="3" class="field-input" /></label>
                <div class="flex justify-end gap-3 sm:col-span-2"><Link href="/patients" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Cancel</Link><button :disabled="form.processing" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50">Save changes</button></div>
            </form>
        </section>
    </AppShell>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import AppShell from '../../Layouts/AppShell.vue';

const props = defineProps({ patient: { type: Object, required: true } });
const bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
const form = useForm({
    name: props.patient.name || '',
    date_of_birth: props.patient.date_of_birth ? String(props.patient.date_of_birth).slice(0, 10) : '',
    gender: props.patient.gender || '',
    blood_group: props.patient.blood_group || '',
    phone: props.patient.phone || '',
    email: props.patient.email || '',
    address: props.patient.address || '',
    allergies: props.patient.allergies || '',
    medical_history: props.patient.medical_history || '',
});
const submit = () => form.put(`/patients/${props.patient.id}`);
</script>

<style scoped>
@reference "../../../css/app.css";

.field-label { @apply block text-xs font-semibold text-slate-600; }
.field-input { @apply mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20; }
.error { @apply mt-1 block text-xs font-medium text-rose-600; }
</style>
