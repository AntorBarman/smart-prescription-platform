<template>
    <div :class="['animate-pulse rounded-lg bg-slate-200', $attrs.class]" :style="{ height: `${height}px` }" />
</template>

<script setup>
defineOptions({ inheritAttrs: false });
defineProps({ height: { type: [String, Number], default: 16 } });
</script>
