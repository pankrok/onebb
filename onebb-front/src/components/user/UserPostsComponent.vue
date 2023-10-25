<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue';
import {useRoute} from 'vue-router'
import useApi from './../../hooks/useApi';
import PostComponent from '../ui/PostComponent.vue';
import Box from '../box/BoxComponent.vue';
import type { IHydra, IPost, IUser } from '@/interfaces';

defineProps<{user: IUser}>();

const {get} = useApi();
const route = useRoute();
const userPosts = ref<IPost[]>([]);
const queryParams = reactive({ 
    page: 1,
    limit: 20,
    lastPage: false,
})

async function getUserPosts() {
    const {parsedResponse} = await get<IHydra<IPost>>(`users/${route.params.id}/posts?limit=${queryParams.limit}&page=${queryParams.page}`);
    if (parsedResponse !== null) {
        parsedResponse
        userPosts.value.push(...parsedResponse['hydra:member'])
        if (parsedResponse['hydra:view']) {
            if (!parsedResponse['hydra:view']['hydra:next']) {
                queryParams.lastPage = true;
            }
        }
    }
    
}

getUserPosts();

watch(queryParams, () => {
    getUserPosts();
}) 

</script>
<template>
    <div>
        <TransitionGroup
            name="list"
            class="column position-relative margin-x-l"
            mode="in-out"
        >
        <Box v-for="post in userPosts" :box-class="[
            'row',
            'margin-m',
            'border-1',
            'color-white',
            'border-color-primary',
            'border-radius-5',
            'padding-m'
            ]" :key="post.id">
        
        <PostComponent :post="{...post, user}" :key="post.id" no-quote />
         
        </Box>
    </TransitionGroup>
        <button v-if="!queryParams.lastPage" @click="()=>queryParams.page++" class="button button-color-white">Load more posts</button>
    </div>
</template>