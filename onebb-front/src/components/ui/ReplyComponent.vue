<script setup lang="ts">
import { defineAsyncComponent, ref } from 'vue';
import { useUser } from '@/hooks/useUser';
import useApi from '@/hooks/useApi';
import { useRoute } from 'vue-router';

const JoditComponent = defineAsyncComponent(() =>
  import('../JoditComponent.vue')
)

const {callback} = defineProps<{callback: Function}>();
const route = useRoute();
const {isLogged} = useUser();
const {post} = useApi();
const textValue = ref()
const sendReply = async () => {
    if (!isLogged()) {
        return;
    }

    const response = await post('posts', {
        plot: `/api/plots/${route.params.id}`,
        content: textValue.value,
    })

    callback(response.parsedResponse ?? undefined)
}
</script>
<template>
    <div v-if="isLogged()">
        <JoditComponent :value="textValue" @update-event="(e)=>{textValue = e}"/>
        <div class="row margin-m justify-content-flex-end">
            <button class="button button-background-blue button-color-white border-radius-5" @click="sendReply()">
                Send
            </button>
        </div>
    </div>
</template>