<script setup lang="ts">
import { defineAsyncComponent, ref } from 'vue'
import useAxios from '@/hooks/useAxios'
import { useRoute } from 'vue-router'
import useAuthStore from '@/stores/useAuthStore'
import { storeToRefs } from 'pinia'
import { POST_URL } from '@/helpers/api'
import { instanceOf } from '@/hooks/helpers'
import type { IPost } from '@/interfaces'

const JoditComponent = defineAsyncComponent(() => import('../JoditComponent.vue'))

const authStore = useAuthStore()
const props = defineProps<{ callback: Function }>()
const route = useRoute()
const { logged } = storeToRefs(authStore)
const { axios } = useAxios()
const textValue = ref()
const sendReply = async () => {
  if (logged.value === false) {
    return
  }

  const {data} = await axios.post<unknown>(POST_URL, {
    plot: `/api/plots/${route.params.id}`,
    content: textValue.value
  })

  if(instanceOf<IPost>(data)) {
    // fixme: this will be stored in pinia
    props.callback(data)
  }

}
</script>
<template>
  <div v-if="logged">
    <JoditComponent
      :value="textValue"
      @update-event="
        (e) => {
          textValue = e
        }
      "
    />
    <div class="row margin-m justify-content-flex-end">
      <button class="button button-color-green" @click="sendReply()">Send</button>
    </div>
  </div>
</template>
