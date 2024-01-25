<script setup lang="ts">
import useMessengerStore from '@/stores/useMessengerStore';
import { MessengerComponentEnum } from '@/hooks/obbClient';

const messengerStore = useMessengerStore()
</script>

<template>
    <button class="button button-color-white" @click="messengerStore.setComponent(MessengerComponentEnum.Add)">ADD</button>
    <button class="button button-color-white" @click="messengerStore.setComponent(MessengerComponentEnum.Chat)">CHAT</button>
</template>