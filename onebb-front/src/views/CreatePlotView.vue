<script setup lang="ts">
import useAuthStore from '@/stores/useAuthStore';
const authStore = useAuthStore();


</script>
<template>
    <div v-if="authStore.logged">

    </div>
</template>