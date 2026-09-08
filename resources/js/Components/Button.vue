<template>
    <button :type="type" :disabled="disabled" :class="classes">
        <slot />
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: { type: String, default: 'primary' },
    type: { type: String, default: 'button' },
    disabled: { type: Boolean, default: false },
});

const classes = computed(() => [
    'inline-flex items-center justify-center gap-2 rounded-lg px-3.5 py-2 text-sm font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
    props.variant === 'secondary'
        ? 'border border-slate-200 bg-white text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700'
        : 'bg-indigo-600 text-white shadow-sm hover:-translate-y-0.5 hover:bg-indigo-700',
]);
</script>
