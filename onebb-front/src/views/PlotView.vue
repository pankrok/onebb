<script setup lang="ts">
import { ref } from 'vue'
import Box from '@/components/box/BoxComponent.vue'
import type { IPost, IPlot, IHydraView } from '@/interfaces'
import PostComponent from '@/components/ui/PostComponent.vue'
import PaginatorComponent from '@/components/PaginatorComponent.vue'
import ReplyComponent from '@/components/ui/ReplyComponent.vue'
import useAuthStore from '@/stores/useAuthStore'
import { storeToRefs } from 'pinia'
import usePlotStore from '@/stores/usePlotStore'

const authStore = useAuthStore();
const plotStore = usePlotStore();
const { logged } = storeToRefs(authStore)
const {plot, posts, hydraView} = storeToRefs(plotStore);
plotStore.getPlotData();

const cb = (addedPost: IPost | undefined) => {
  if (typeof addedPost === 'undefined') {
    return
  }
  posts.value?.push(addedPost)
}

const reply = ref(false)
const plotClassBox = [
  'row',
  'margin-m',
  'border-1',
  'color-white',
  'border-color-primary',
  'border-radius-5',
  'padding-m'
]

const headerClassBox = [
'color-white',
  'margin-m',
  'border-1',
  'border-color-primary',
  'border-radius-5',
  'padding-m',
  'font-size-14'
]


</script>
<template>
  <TransitionGroup
    tag="div"
    name="list"
    class="column position-relative margin-x-l"
    mode="in-out"
    v-if="plot"
  >
    <Box :boxClass="headerClassBox" key="plot-header">
      <h2 class="font-size-14 font-weight-600 padding-bottom-m margin-none">Plot name</h2>
      <span class="font-size-14"> Pankrok - 4h temu </span>
    </Box>
    <PaginatorComponent :hydraView="hydraView" />
    <Box v-for="post in posts" :box-class="plotClassBox" :key="post.id">
      <PostComponent :post="post" :quote="logged" />
    </Box>
    <div class="row justify-content-flex-end" v-if="logged">
      
      <button class="button button-color-white col-12 margin-m"                         
        key="reply-btn"
        v-if="!reply"
        @click="reply = !reply"
      >
        Reply
      </button>

      <Box :wrapperClass="['col-12']" v-if="reply" key="reply">
        <ReplyComponent :callback="cb" />
      </Box>
    </div>

    <PaginatorComponent :hydraView="hydraView" />
  </TransitionGroup>
</template>
