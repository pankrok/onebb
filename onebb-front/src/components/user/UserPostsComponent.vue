<script setup lang="ts">
import { reactive, ref, watch } from 'vue';
import {useRoute} from 'vue-router'
import useAxios from '@/hooks/useAxios';
import PostComponent from '../ui/PostComponent.vue';
import Box from '../box/BoxComponent.vue';
import type { IHydra, IPost, IUser } from '@/interfaces';
import { USER_URL } from '@/helpers/api';
import { instanceOf } from '@/hooks/helpers';

defineProps<{user: IUser}>();

const {axios} = useAxios();
const route = useRoute();
const userPosts = ref<IPost[]>([]);
const queryParams = reactive({ 
    page: 1,
    limit: 20,
    lastPage: false,
})

async function getUserPosts() {
    const {data} = await axios.get<unknown>(`${USER_URL}/${route.params.id}/posts?limit=${queryParams.limit}&page=${queryParams.page}`);
    if (instanceOf<IHydra<IPost>>(data)) {
        userPosts.value.push(...data['hydra:member'])
        if (data['hydra:view']) {
            if (!data['hydra:view']['hydra:next']) {
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
        
            {{ post }}
         
        </Box>
    </TransitionGroup>
        <button v-if="!queryParams.lastPage" @click="()=>queryParams.page++" class="button button-color-white">Load more posts</button>
    </div>
</template>