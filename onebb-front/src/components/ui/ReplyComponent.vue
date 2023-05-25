<script setup lang="ts">
import { ref } from 'vue';
import JoditComponent from '../JoditComponent.vue';

const textValue = ref()
const sendReply = () => {
    console.log(textValue.value)
}
</script>
<template>
    <div>
        <JoditComponent :value="textValue" @update-event="(e)=>{textValue = e}"/>
        <div class="row margin-m justify-content-flex-end">
            <button class="button button-background-blue button-color-white border-radius-5" @click="sendReply()">
                Send
            </button>
        </div>
    </div>
</template>