<script setup lang="ts">
import { useUser } from '@/hooks/useUser';
import ImageComponent from './ui/ImageComponent.vue';
const {getUser} = useUser();
const {avatar, slug, uid} = getUser()
defineProps<{
    loginToggle: Function,
}>()
</script>
<template>
    <div
        class="col-12 border-bottom-1 border-color-blue background-color-dark row justify-content-space-between box-shadow-dark padding-y-l">
        <div class="col-3">
            <router-link :to="{
                name: 'Home',
            }" class="font-size-18 font-weight-800 col-3">
                OneBB
            </router-link>
        </div>
        <div class="col-9 row justify-content-flex-end">
            <span v-if="uid > 0">
                <ImageComponent :size="[20, 20]" :src="avatar" :alt="slug" />
            </span>
            <span 
                v-else
                class="button cursor-pointer border-radius-10 text-align-center padding-s"
                @click="loginToggle()"
            >
                Login
            </span>
        </div>
    </div>
</template>