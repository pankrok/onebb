<script setup lang="ts">
import type { IHydraView } from '@/interfaces/OnebbInterfaces';
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const props = defineProps<{ hydraView?: IHydraView }>()

const route = useRoute();
const {hydraView} = props;


const paginator = computed(() => {
    const p = {
        goPrev: false,
        goNext: false,
        goFirst: false,
        goLast: '',
    }

    if(hydraView) {
        if (hydraView['hydra:last']) {
            let [uri, page] = hydraView['hydra:last'].split('=');
            p.goLast = page;
        }
        p.goPrev = hydraView['hydra:previous'] ? true : false;
        p.goNext = hydraView['hydra:next'] ? true : false;
        p.goFirst = hydraView['hydra:first'] ? true : false;
        
    }
    console.log({p, hydraView, props})
    return p;
})
//const goNext = computed(()=>( hydraView['hydra:next'] ? false : true) )) ( hydraView['hydra:previous'] ? false : true)

</script>
<template>
    <div class="row justify-content-space-between margin-l">
        <div>
            <router-link v-if="paginator.goFirst" :to="{ params: { page: 1 } }"
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
            <router-link v-if="paginator.goLast.length > 0" :to="{ params: { page: paginator.goLast } }" 
                class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m"
                :class="{ 'button-disabled': !paginator.goLast }">
                Last
            </router-link>
        </div>
    </div>
</template>
