<script setup lang="ts">
import { defineProps } from 'vue';

defineProps<{
    classList?: object|string[]
}>()
</script>
<template>
    <div class="border-top-1 border-color-dark" :class="{classList}">
        <slot></slot>
    </div>
</template>