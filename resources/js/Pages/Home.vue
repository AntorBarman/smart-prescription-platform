<template>
    <div class="min-h-screen overflow-hidden bg-slate-950 text-white">
        <nav class="relative z-10 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8">
                <Link href="/" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-400/20">
                        <HeartIcon class="h-5 w-5" />
                    </span>
                    <span>
                        <span class="block text-sm font-bold tracking-tight">MediPrescribe</span>
                        <span class="block text-[11px] text-slate-400">Healthcare, connected</span>
                    </span>
                </Link>

                <div class="hidden items-center gap-7 text-sm text-slate-300 md:flex">
                    <a href="#features" class="transition hover:text-white">Why MediPrescribe</a>
                    <a href="#workflow" class="transition hover:text-white">How it works</a>
                    <template v-if="user">
                        <Link :href="dashboardHref" class="transition hover:text-white">Dashboard</Link>
                        <Link href="/patients" class="transition hover:text-white">Patients</Link>
                        <Link href="/prescriptions" class="transition hover:text-white">Prescriptions</Link>
                        <form @submit.prevent="logout">
                            <button type="submit" class="rounded-full border border-white/15 px-4 py-2 font-semibold text-white transition hover:border-cyan-300 hover:text-cyan-300">
                                Sign out
                            </button>
                        </form>
                    </template>
                    <template v-else>
                        <Link href="/login" class="transition hover:text-white">Sign in</Link>
                        <Link href="/register" class="rounded-full bg-cyan-400 px-5 py-2.5 font-semibold text-slate-950 transition hover:bg-cyan-300">Get started</Link>
                    </template>
                </div>

                <div class="flex items-center gap-2 md:hidden">
                    <Link v-if="user" :href="dashboardHref" class="rounded-full border border-white/15 px-3 py-2 text-xs font-semibold">Dashboard</Link>
                    <Link v-else href="/login" class="rounded-full border border-white/15 px-3 py-2 text-xs font-semibold">Sign in</Link>
                    <form v-if="user" @submit.prevent="logout">
                        <button type="submit" class="rounded-full bg-cyan-400 px-3 py-2 text-xs font-bold text-slate-950">Sign out</button>
                    </form>
                    <Link v-else href="/register" class="rounded-full bg-cyan-400 px-3 py-2 text-xs font-bold text-slate-950">Join</Link>
                </div>
            </div>
        </nav>

        <main>
            <section class="relative isolate">
                <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_75%_15%,rgba(34,211,238,0.18),transparent_32%),radial-gradient(circle_at_10%_40%,rgba(99,102,241,0.18),transparent_30%)]" />
                <div class="mx-auto grid max-w-7xl items-center gap-14 px-5 py-20 sm:px-8 lg:grid-cols-[1.05fr_0.95fr] lg:py-28">
                    <div>
                        <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1.5 text-xs font-semibold text-cyan-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-300 shadow-[0_0_12px_#67e8f9]" />
                            A smarter way to care
                        </div>
                        <h1 class="max-w-3xl text-4xl font-bold leading-[1.08] tracking-tight sm:text-6xl">
                            Healthcare workflows, <span class="text-cyan-300">beautifully simple.</span>
                        </h1>
                        <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                            Create, verify, and manage digital prescriptions in one secure workspace built for doctors, patients, and pharmacies.
                        </p>
                        <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                            <Link :href="user ? dashboardHref : '/register'" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-400 px-6 py-3.5 text-sm font-bold text-slate-950 shadow-xl shadow-cyan-500/15 transition hover:-translate-y-0.5 hover:bg-cyan-300">
                                {{ user ? 'Open your workspace' : 'Start for free' }}
                                <ArrowRightIcon class="h-4 w-4" />
                            </Link>
                            <Link :href="user ? '/prescriptions' : '/login'" class="inline-flex items-center justify-center rounded-xl border border-white/15 px-6 py-3.5 text-sm font-semibold text-white transition hover:border-white/30 hover:bg-white/5">
                                {{ user ? 'View prescriptions' : 'Sign in to continue' }}
                            </Link>
                        </div>
                        <div class="mt-10 flex flex-wrap gap-x-7 gap-y-3 text-xs text-slate-400">
                            <span class="flex items-center gap-2"><CheckCircleIcon class="h-4 w-4 text-cyan-300" /> Secure by design</span>
                            <span class="flex items-center gap-2"><CheckCircleIcon class="h-4 w-4 text-cyan-300" /> QR verification</span>
                            <span class="flex items-center gap-2"><CheckCircleIcon class="h-4 w-4 text-cyan-300" /> Role-based access</span>
                        </div>
                    </div>

                    <div class="relative mx-auto w-full max-w-md">
                        <div class="absolute -inset-8 rounded-[2.5rem] bg-cyan-400/10 blur-3xl" />
                        <div class="relative rounded-[2rem] border border-white/15 bg-white/[0.08] p-4 shadow-2xl backdrop-blur-xl">
                            <div class="rounded-[1.5rem] bg-slate-900 p-5">
                                <div class="flex items-center justify-between">
                                    <div><p class="text-xs text-slate-400">Today, 09:41 AM</p><p class="mt-1 text-lg font-bold">Care overview</p></div>
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-300/15 text-cyan-300"><ChartBarIcon class="h-5 w-5" /></span>
                                </div>
                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl bg-white/5 p-4"><p class="text-2xl font-bold">{{ homeStats.activePatients }}</p><p class="mt-1 text-xs text-slate-400">Registered patients</p></div>
                                    <div class="rounded-2xl bg-cyan-300/10 p-4"><p class="text-2xl font-bold text-cyan-300">{{ homeStats.prescriptionsToday }}</p><p class="mt-1 text-xs text-slate-400">Prescriptions today</p></div>
                                </div>
                                <div class="mt-3 rounded-2xl bg-white/5 p-4">
                                    <div class="flex items-center justify-between text-xs"><span class="text-slate-400">Medicine catalog</span><span class="text-cyan-300">{{ homeStats.medicines }} active</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="bg-white py-20 text-slate-900 sm:py-24">
                <div class="mx-auto max-w-7xl px-5 sm:px-8">
                    <div class="max-w-2xl"><p class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600">One connected platform</p><h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Everything your care team needs to move faster.</h2></div>
                    <div class="mt-12 grid gap-5 md:grid-cols-3">
                        <article v-for="feature in features" :key="feature.title" class="rounded-3xl border border-slate-200 bg-slate-50 p-6 transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/60">
                            <span :class="['flex h-12 w-12 items-center justify-center rounded-2xl', feature.bg]"><component :is="feature.icon" class="h-6 w-6" /></span>
                            <h3 class="mt-6 text-lg font-bold">{{ feature.title }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ feature.description }}</p>
                        </article>
                    </div>
                </div>
            </section>

            <section id="workflow" class="bg-slate-100 py-20 text-slate-900 sm:py-24">
                <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-2 lg:items-center">
                    <div><p class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600">Simple workflow</p><h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">From consultation to pharmacy in three clear steps.</h2></div>
                    <div class="space-y-4">
                        <div v-for="(step, index) in steps" :key="step.title" class="flex gap-4 rounded-2xl bg-white p-5 shadow-sm"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white">{{ index + 1 }}</span><div><h3 class="font-bold">{{ step.title }}</h3><p class="mt-1 text-sm text-slate-500">{{ step.description }}</p></div></div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/10 bg-slate-950 px-5 py-8 text-center text-xs text-slate-500 sm:px-8">
            <p>© 2026 MediPrescribe. Secure digital care for modern teams.</p>
        </footer>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon, BeakerIcon, ChartBarIcon, CheckCircleIcon, HeartIcon, QrCodeIcon, UsersIcon } from '@heroicons/vue/24/outline';

const props = defineProps({ homeStats: { type: Object, default: () => ({ activePatients: 0, prescriptionsToday: 0, medicines: 0 }) } });
const homeStats = computed(() => props.homeStats);
const page = usePage();
const user = computed(() => page.props.auth?.user);
const dashboardHref = computed(() => {
    const roles = user.value?.roles || [];
    if (roles.includes('ADMIN')) return '/admin/dashboard';
    if (roles.includes('DOCTOR')) return '/doctor/dashboard';
    if (roles.includes('PHARMACIST') || roles.includes('PHARMACY_MANAGER')) return '/pharmacy/dashboard';
    return '/dashboard';
});

const logout = () => router.post('/logout');
const features = [
    { title: 'Secure prescriptions', description: 'Issue tamper-resistant prescriptions with instant QR verification at the pharmacy.', icon: QrCodeIcon, bg: 'bg-cyan-100 text-cyan-700' },
    { title: 'Patient-first records', description: 'Keep every patient, medicine, and prescription detail organized and accessible.', icon: UsersIcon, bg: 'bg-indigo-100 text-indigo-700' },
    { title: 'Pharmacy visibility', description: 'Track inventory, sales, and fulfilment from one calm, focused workspace.', icon: BeakerIcon, bg: 'bg-emerald-100 text-emerald-700' },
];
const steps = [
    { title: 'Create a prescription', description: 'Doctors add patient details and medicines in seconds.' },
    { title: 'Share securely', description: 'The patient receives a unique prescription with QR verification.' },
    { title: 'Verify and fulfil', description: 'Pharmacies scan, confirm, and complete the order with confidence.' },
];
</script>
