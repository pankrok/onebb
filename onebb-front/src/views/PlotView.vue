<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import usePlot from '@/hooks/usePlot';
import Box from '@/components/box/BoxComponent.vue'
import type { IPost, IPlot } from '@/interfaces/OnebbInterfaces';
import PostComponent from '@/components/ui/PostComponent.vue';
import PaginatorComponent from '@/components/PaginatorComponent.vue';
import ReplyComponent from '@/components/ui/ReplyComponent.vue';
import { useUser } from '@/hooks/useUser';

const { isLogged } = useUser();
const boxStyles = ['background-blue', 'border-radius-5', 'color-white', 'font-size-18', 'font-weight-500', 'padding-l'];
const route = useRoute();
const cb = (addedPost: IPost | undefined) => {
    if (typeof addedPost === 'undefined') {
        return;
    }

    posts.value?.push(addedPost)
}
const plot = ref<IPlot>();
const posts = ref<IPost[]>();
const hydraView = ref();
const reply = ref(false);

usePlot(route.params.id, route.params.page ?? 1).then(response => {
    const { plotResponse, postsResponse } = response;
    plot.value = plotResponse;
    if (postsResponse) {
        posts.value = postsResponse['hydra:member'];
        // @ts-ignore
        hydraView.value = postsResponse['hydra:view'];

    }
})

</script>
<template>
    <TransitionGroup tag="div" name="list" class="column position-relative margin-x-l" mode="in-out" v-if="plot">
        <Box :boxClass="boxStyles" key="plot-header">
            {{ plot.name }}
        </Box>

        <Box v-for="post in posts" :key="post.id">
            <PostComponent :post="post" />
        </Box>
        <div class="row justify-content-flex-end" v-if="isLogged()">
            <button key="reply-btn" v-if="!reply" class="button button-background-green button-color-white border-radius-5"
                @click="reply = !reply">
                Reply
            </button>

            <Box :wrapperClass="['col-12']" v-if="reply" key="reply">
                <ReplyComponent :callback="cb" />
            </Box>
        </div>

        <PaginatorComponent :hydraView="hydraView" />
    </TransitionGroup>
</template>