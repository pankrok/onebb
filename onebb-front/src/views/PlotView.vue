<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import usePlot from '@/hooks/usePlot';
import Box from '@/components/box/BoxComponent.vue'
import type { IPost, IPlot } from '@/interfaces/OnebbInterfaces';
import PostComponent from '@/components/ui/PostComponent.vue';
import PaginatorComponent from '@/components/PaginatorComponent.vue';

const boxStyles = ['background-blue', 'border-radius-5', 'color-white', 'font-size-18', 'font-weight-500', 'padding-l', 'margin-l'];
const route = useRoute();
const plot = ref<IPlot>();
const posts = ref<IPost[]>();

usePlot(route.params.id, route.params.page ?? 1).then(response => {
    console.log({debugPlotResponse: response})
    const { plotResponse, postsResponse } = response;
    plot.value = plotResponse;
    posts.value = postsResponse;
})

</script>
<template>

    <div class="column" v-if="plot">
        <Box :boxClass="boxStyles">
            {{ plot.name }}
        </Box>
        <Box v-for="post in posts">
           <PostComponent :post="post" />
        </Box>
        <PaginatorComponent />
    </div>

</template>