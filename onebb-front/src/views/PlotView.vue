<script setup lang="ts">
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import api from '@/services/api/api';
import { IPlot } from '@/interfaces/obbApiInterface';
import { PLOT } from '@/services/api/obbResources';
import { parseUsername, parseDate } from '@/services/helpers/parsers';

const store = useStore();
const route = useRoute();
const id: number = Number(route.params.id);
const page: number = Number(route.params.page) ?? 1;
const plot = ref<IPlot>();
const posts = ref<any>(); // FIXME
const next = ref<boolean>(false);
const prev = ref<boolean>(false);

api.get<IPlot>({resource: PLOT, id: id,}).then((res) => {
    plot.value = res.body;
    
    api.get<any>({resource:PLOT, id: id, query: `/posts?page=${page}`}).then((postsResponse) => {
        posts.value = postsResponse.body;
        next.value = postsResponse.next;
        prev.value = postsResponse.prev;
        console.log({postsResponse});
    })
})

</script>
<template>
    <span v-if="plot">
        <h1>{{ plot.name }}</h1>
        <div v-if="posts" v-for="post in posts">
            <div v-html="post.content"></div> | {{ parseDate(post.created_at) }} | <span v-html="parseUsername(post.user.username, post.user.user_group.html_code)"></span>
        </div>

        <div v-if="prev">
            <RouterLink
                :to="{
                    name: 'Plot',
                    params: { slug: plot.slug, id: plot.id, page: (page-1) },
                }"
                >
                prev
            </RouterLink>
        </div>

        <div v-if="next">
            <RouterLink
                :to="{
                    name: 'Plot',
                    params: { slug: plot.slug, id: plot.id, page: (page+1) },
                }"
                >
                next
            </RouterLink>
        </div>
    </span>
    
</template>