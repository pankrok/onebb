<script setup lang="ts">
defineProps<{
    url: string,
    size: 'img-size-s' | 'img-size-m' | 'img-size-l',
    mobileSize: 'img-size-mobile-s' | 'img-size-mobile-m' | 'img-size-mobile-l'
}>();

// @ts-ignore
const baseURL = document.getElementById('app')?.getAttribute('data-url') + document.getElementById('app')?.getAttribute('data-obb')
</script>
<template>
    <img
        :src="baseURL + url ?? 'assets/avatar.png'"
        class="border-radius-circel padding-x-l"
        :class="[size, mobileSize]"
    />
</template>