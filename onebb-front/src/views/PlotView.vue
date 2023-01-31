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
const plot = ref<IPlot>();
const posts = ref<any>(); // FIXME

api.get<IPlot>({resource: PLOT, id: id}).then((res) => {
    plot.value = res.body;
    
    api.get<any>({resource:PLOT, id: id, query: '/posts'}).then((postsResponse) => {
        posts.value = postsResponse.body;
        console.log({postsResponse});
    })
    console.log(plot.value);
})

</script>
<template>
    <span v-if="plot">
        <h1>{{ plot.name }}</h1>
        <div v-if="posts" v-for="post in posts">
            <div v-html="post.content"></div> | {{ parseDate(post.created_at) }} | <span v-html="parseUsername(post.user.username, post.user.user_group.html_code)"></span>
        </div>
    </span>
    
</template>