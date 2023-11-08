<script setup lang="ts">
import type { IHydraView } from '@/interfaces';
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps<{ hydraView: IHydraView |null }>()

const route = useRoute();



const paginator = computed(() => {
    const p = {
        goPrev: false,
        goNext: false,
        goFirst: false,
        goLast: '',
    }

    if(props.hydraView) {
        if (props.hydraView['hydra:last']) {
            let [uri, page] = props.hydraView['hydra:last'].split('=');
            p.goLast = page;
        }
        p.goPrev = props.hydraView['hydra:previous'] ? true : false;
        p.goNext = props.hydraView['hydra:next'] ? true : false;
        p.goFirst = props.hydraView['hydra:first'] ? true : false;
        
    }
    console.log({p, props, hydraView: props.hydraView})
    return p;
})
//const goNext = computed(()=>( hydraView['hydra:next'] ? false : true) )) ( hydraView['hydra:previous'] ? false : true)

</script>
<template>
    <div class="row justify-content-space-between margin-m">
        <div>
            <router-link v-if="paginator.goFirst" :to="{ params: { page: 1 } }"
                class="button button-color-white"
                :class="{ 'button-disabled': !paginator.goFirst }">
                First
            </router-link>
            <router-link v-if="paginator.goPrev" :to="{ params: { page: `${Number(route.params.page) - 1}` } }" 
                class="button button-color-white"
                :class="{ 'button-disabled': !paginator.goPrev }">
                Back
            </router-link>
        </div>
        <div>
            <router-link v-if="paginator.goNext" :to="{ params: { page: `${Number(route.params.page) + 1}` } }" 
                class="button button-color-white"
                :class="{ 'button-disabled': !paginator.goNext }">
                Next
            </router-link>
            <router-link v-if="paginator.goLast.length > 0" :to="{ params: { page: paginator.goLast } }" 
                class="button button-color-white"
                :class="{ 'button-disabled': !paginator.goLast }">
                Last
            </router-link>
        </div>
    </div>
</template>
