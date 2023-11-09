<script setup lang="ts">
import BoxComponent from '@/components/box/BoxComponent.vue';
import InputComponent from '@/components/ui/InputComponent.vue';
import useAuthStore from '@/stores/useAuthStore';
import usePlot from '@/hooks/usePlot';
import { defineAsyncComponent, ref } from 'vue';

const JoditComponent = defineAsyncComponent(() => import('@/components/JoditComponent.vue'))
const authStore = useAuthStore();
const {createPlot} = usePlot();
const payload = ref({
    name: '',
    content: ''
})
function updateContent(text: string) {
    payload.value.content = text;
}


</script>
<template>
    <BoxComponent>
        <div class="row" v-if="!authStore.logged">
        <InputComponent
          label="Topic"
          :wrapper-class-list="['row col-12']"
          :class-list="['col-12', 'color-white']"
          id="title"
          type="text"
          v-model="payload.name"
        />
        <div class="col-12">
            <JoditComponent
            :value="payload.content"
            @update-event="updateContent" 
        />

        </div>
        <div class="col-12 row margin-m justify-content-flex-end">
            <button class="button button-color-green" @click="createPlot(payload)">Send</button>
        </div>
    </div>
    </BoxComponent>
    
</template>