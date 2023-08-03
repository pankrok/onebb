<script setup lang="ts">
import type { IHydraView } from '@/interfaces/OnebbInterfaces';
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const { hydraView } = defineProps<{ hydraView?: IHydraView }>()

const route = useRoute();
const router = useRouter();



const paginator = computed(() => {

    const goPrev = hydraView ? (hydraView['hydra:previous'] ? false : true) : false;
    const goNext = hydraView ? (hydraView['hydra:previous'] ? false : true) : false;
    const goFirst = hydraView ? (hydraView['hydra:first'] ? false : true) : false;
    const goLast = hydraView ? (hydraView['hydra:last'] ? Number(hydraView['hydra:last']) : true) : false;
    return { goPrev, goNext, goFirst, goLast }
})
//const goNext = computed(()=>( hydraView['hydra:next'] ? false : true) )) ( hydraView['hydra:previous'] ? false : true)

</script>
<template>
    <div class="row justify-content-space-between margin-l">
        <div>
            <router-link v-if="!paginator.goFirst" :to="{ params: { page: 1 } }"
                class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m"
                :class="{ 'button-disabled': !paginator.goFirst }">
                First
            </router-link>
            <router-link v-if="paginator.goPrev" :to="{ params: { page: `${Number(route.params.page) - 1}` } }" 
                class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m"
                :class="{ 'button-disabled': !paginator.goPrev }">
                Back
            </router-link>
        </div>
        <div>
            <router-link v-if="paginator.goNext" :to="{ params: { page: `${Number(route.params.page) + 1}` } }" 
                class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m"
                :class="{ 'button-disabled': !paginator.goNext }">
                Next
            </router-link>
            <router-link v-if="paginator.goLast" :to="{ params: { page: paginator.goLast } }" 
                class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m"
                :class="{ 'button-disabled': !paginator.goLast }">
                Last
            </router-link>
        </div>
    </div>
</template>
