<template>
    <span :class="classes"><span class="h-1.5 w-1.5 rounded-full bg-current" />{{ label }}</span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, default: 'info' },
    label: { type: String, default: '' },
});

const classes = computed(() => ({
    success: 'bg-emerald-50 text-emerald-700',
    warning: 'bg-amber-50 text-amber-700',
    danger: 'bg-rose-50 text-rose-700',
    info: 'bg-indigo-50 text-indigo-700',
}[props.status] || 'bg-slate-100 text-slate-600') + ' inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium');
</script>
