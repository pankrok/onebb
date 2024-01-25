<script setup lang="ts">
import PlotComponent from '@/components/ui/partials/PlotComponent.vue'
import { usePlot } from '@/hooks/obbClient'
import type { IPost } from '@/interfaces'
import instanceOf from '@/utils/instanceOf'
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute();
const  data = ref();
const {getPlot, addPostToPlot, updatePost} = usePlot()
getPlot().then((response) => {
    console.log('getPlot', {response})
  data.value = response;
})


async function modUpdate(id: number, val: { content?: string | undefined; hidden?: boolean | undefined; }) {
  const x = await updatePost(id, val);
  console.log(x);
}

async function submitPost(payload: string) {
  const res = await addPostToPlot(payload, `/api/plots/${route.params.id}`)
  if(instanceOf<IPost>(res)) {
    data.value.posts.push(res);
  }
}

</script>

<template>
  <plot-component v-bind="data" @submit="submitPost" @mod="modUpdate" />
</template>
