<script setup lang="ts">
import { useUser } from '@/hooks/useUser';
const { login, getUser } = useUser();

const doIt = async() => {
    const response = await login({username: 'grzyb', password: 'grzyb@s89.eu'})
    console.log(getUser(), {response})
}

</script>
<template>
    <div class="col-12 border-bottom-1 border-color-blue background-color-dark row justify-content-space-between box-shadow-dark padding-y-l">
        <div class="col-3">
        <router-link :to="{
            name: 'Home',
            }"
            class="font-size-18 font-weight-800 col-3"
        >
        OneBB
        </router-link>
    </div>
        <div class="col-9 row justify-content-flex-end">
            <span class="button cursor-pointer border-radius-10 text-align-center padding-s" @click="doIt()">Login</span>
        </div>
    </div>
</template>