<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import usePlot from '@/hooks/usePlot'
import Box from '@/components/box/BoxComponent.vue'
import type { IPost, IPlot, IHydraView } from '@/interfaces'
import PostComponent from '@/components/ui/PostComponent.vue'
import PaginatorComponent from '@/components/PaginatorComponent.vue'
import ReplyComponent from '@/components/ui/ReplyComponent.vue'
import { useUser } from '@/hooks/useUser'

const { isLogged } = useUser()

const cb = (addedPost: IPost | undefined) => {
  if (typeof addedPost === 'undefined') {
    return
  }

  posts.value?.push(addedPost)
}
const plot = ref<IPlot|null>()
const posts = ref<IPost[]>()
const hydraView = ref<IHydraView>()
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

usePlot().then(({ plotResponse, postsResponse }) => {
  plot.value = plotResponse
  console.log({ plotResponse, postsResponse })
  if (postsResponse) {
    posts.value = postsResponse['hydra:member']
    hydraView.value = postsResponse['hydra:view']
  }
})
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
      <PostComponent :post="post" />
    </Box>
    <div class="row justify-content-flex-end" v-if="isLogged()">
      
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
