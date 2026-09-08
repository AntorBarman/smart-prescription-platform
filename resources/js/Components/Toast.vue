<template>
    <Transition name="toast">
        <div v-if="show" class="fixed right-4 top-4 z-50 flex max-w-sm items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-lg">
            <CheckCircleIcon v-if="type === 'success'" class="h-5 w-5 shrink-0 text-emerald-500" />
            <ExclamationCircleIcon v-else class="h-5 w-5 shrink-0 text-amber-500" />
            <p class="flex-1 text-sm font-medium text-slate-700">{{ message }}</p>
            <button type="button" class="text-slate-400 hover:text-slate-700" aria-label="Close notification" @click="$emit('close')">×</button>
        </div>
    </Transition>
</template>

<script setup>
import { CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
    show: { type: Boolean, default: false },
    message: { type: String, default: '' },
    type: { type: String, default: 'success' },
});
defineEmits(['close']);
</script>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: all 0.2s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
