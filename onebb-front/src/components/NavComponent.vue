<script setup lang="ts">
import { useUser } from '@/hooks/useUser';

import UserNavComponent from './ui/user/UserNavComponent.vue';
const {getUser, logout} = useUser();
const user = getUser()

defineProps<{
    loginToggle: Function,
    registerToggle: Function,
}>()


</script>
<template>
    <div
        class="col-12 border-bottom-1 border-color-blue background-color-dark row justify-content-space-between box-shadow-dark padding-y-l">
        <div class="col-3 aligne-items-center row">
            <router-link :to="{
                name: 'Home',
            }" class="font-size-18 font-weight-800 col-3">
                OneBB
            </router-link>
        </div>
        <div class="col-9 row justify-content-flex-end">
            <UserNavComponent :user="user" :logout="logout" />
            <div v-if="user.uid === 0" class="padding-x-m">
                <span 
                    
                    class="button cursor-pointer border-radius-10 text-align-center padding-s color-black"
                    @click="loginToggle()"
                >
                    Sign in
                </span>
                <span 
                    class="button cursor-pointer border-radius-10 text-align-center padding-s  color-black"
                    @click="registerToggle()"
                >
                    Sign up
                </span>
            </div>
        </div>
    </div>
</template>