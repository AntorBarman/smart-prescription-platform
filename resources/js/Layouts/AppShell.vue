<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-60 flex-col border-r border-slate-200 bg-white lg:flex">
            <slot name="sidebar" />
        </aside>

        <Transition name="fade">
            <div v-if="mobileOpen" class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden" @click="mobileOpen = false" />
        </Transition>
        <Transition name="slide">
            <aside v-if="mobileOpen" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white lg:hidden">
                <slot name="sidebar" />
            </aside>
        </Transition>

        <div class="min-h-screen lg:pl-60">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 lg:hidden" aria-label="Open navigation" @click="mobileOpen = true">
                        <Bars3Icon class="h-5 w-5" />
                    </button>
                    <slot name="topbar">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">{{ title }}</p>
                            <p v-if="subtitle" class="text-xs text-slate-500">{{ subtitle }}</p>
                        </div>
                    </slot>
                    <button type="button" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600" @click="logout">
                        Sign out
                    </button>
                </div>
            </header>

            <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
                <slot />
            </main>
        </div>

        <nav class="fixed inset-x-0 bottom-0 z-30 grid grid-cols-4 border-t border-slate-200 bg-white/95 px-2 py-2 backdrop-blur lg:hidden">
            <Link v-for="item in mobileNav" :key="item.label" :href="item.href" class="flex flex-col items-center gap-1 rounded-lg py-1.5 text-[10px] font-medium text-slate-500 hover:bg-indigo-50 hover:text-indigo-600">
                <component :is="item.icon" class="h-5 w-5" />
                {{ item.label }}
            </Link>
        </nav>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Bars3Icon, HomeIcon, UsersIcon, ClipboardDocumentListIcon, BeakerIcon } from '@heroicons/vue/24/outline';

defineProps({
    title: { type: String, default: 'MediPrescribe' },
    subtitle: { type: String, default: '' },
});

const mobileOpen = ref(false);
const mobileNav = [
    { label: 'Overview', href: '/doctor/dashboard', icon: HomeIcon },
    { label: 'Patients', href: '/patients', icon: UsersIcon },
    { label: 'Prescriptions', href: '/prescriptions', icon: ClipboardDocumentListIcon },
    { label: 'Medicines', href: '/medicines', icon: BeakerIcon },
];
const logout = () => router.post('/logout');
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.slide-enter-active, .slide-leave-active { transition: transform 0.2s ease; }
.slide-enter-from, .slide-leave-to { transform: translateX(-100%); }
</style>
