<script setup lang="ts"> 
import ImageComponent from '../ImageComponent.vue';
import type {ITokenResponse} from "../../../interfaces/OnebbInterfaces";
import BoxComponent from '../../box/BoxComponent.vue'
import {ref} from 'vue';

defineProps<{
    user: ITokenResponse
    logout: Function,
}>();

const menu = ref(false)

</script>
<template>
    <div v-if="user.uid !== 0" class="row aligne-items-center justify-content-center padding-x-m">
        <ImageComponent :size="[45, 45]" :src="user.avatar" :alt="user.slug" rounded @click="menu = !menu"/>
        <div v-if="menu" class="position-relative">
            <BoxComponent :wrapperClass="['position-absolute', 'sidemenu']" >
                <div class="column padding-l">
                <span class="pointer" @click="logout()">Logout</span>
                </div>

            </BoxComponent>
        </div>
    </div>
</template>

<style scoped>
    .sidemenu {
        top: 50px;
        right: 15px;
    }
</style>