<script setup lang="ts">
import { defineProps } from 'vue';

defineProps<{
    size: [number, number],
    src: string,
    alt: string,
    rounded?: boolean,
}>();

const base = import.meta.env.VITE_OBB_DOMAIN;

</script>

<template>
    <img 
        :src="base  + src" 
        :alt="alt" 
        :width="size[0]"
        :height="size[1]"
        :class="{'border-radius-circel': rounded}"
    />
</template>