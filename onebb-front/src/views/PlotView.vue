<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import usePlot from '@/hooks/usePlot';
import Box from '@/components/box/BoxComponent.vue'
import type { IPost, IPlot } from '@/interfaces/OnebbInterfaces';
import PostComponent from '@/components/ui/PostComponent.vue';
import PaginatorComponent from '@/components/PaginatorComponent.vue';
import ReplyComponent from '@/components/ui/ReplyComponent.vue';

const boxStyles = ['background-blue', 'border-radius-5', 'color-white', 'font-size-18', 'font-weight-500', 'padding-l', 'margin-l'];
const route = useRoute();
const plot = ref<IPlot>();
const posts = ref<IPost[]>();
const next = ref(false);
const reply = ref(false);

usePlot(route.params.id, route.params.page ?? 1).then(response => {
    console.log({debugPlotResponse: response})
    const { plotResponse, postsResponse } = response;
    plot.value = plotResponse;
    if (postsResponse) {
        posts.value = postsResponse['hydra:member'];
        next.value = postsResponse['hydra:next'] ? true : false
        
    }
})

</script>
<template>
    <TransitionGroup tag="div" name="list" class="column position-relative" mode="in-out" v-if="plot" >
        <Box :boxClass="boxStyles" key="plot-header">
            {{ plot.name }}
        </Box>
        
        <Box v-for="post in posts" :key="post.id">
           <PostComponent :post="post" />
        </Box>
        
        <button 
            key="reply-btn"
            v-if="!reply"
            class="button button-background-green button-color-white border-radius-5"
            @click="reply = !reply"  
        >
            Reply
        </button>
        
        <Box v-if="reply" key="reply">
            <ReplyComponent />
        </Box>
        

        <PaginatorComponent :next="next"/>
    </TransitionGroup>


</template>