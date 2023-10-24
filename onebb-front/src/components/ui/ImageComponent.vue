<script setup lang="ts">
import { defineProps } from 'vue';

defineProps<{
    size: [number, number],
    src: string,
    alt: string,
    rounded?: boolean,
}>();
</script>

<template>
    <img 
        :src="'https://bdev.s89.eu/' + src" 
        :alt="alt" 
        :width="size[0]"
        :height="size[1]"
        :class="{'border-radius-circel': rounded}"
    />
</template>