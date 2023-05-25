<script setup lang="ts">
import { defineProps } from 'vue';
import type { IPost } from '@/interfaces/OnebbInterfaces';
import { useUser } from '@/hooks/useUser';
import Image from '@/components/ui/ImageComponent.vue'
import { useMoment } from '@/hooks/useMoment';

const props = defineProps<{ post: IPost }>()
const user = useUser();
const {parse} = useMoment();
const created_at = parse(props.post.created_at)
</script>
<template>
    <div class="row margin-m">
        <div class="col-2 column text-align-center">
            <div class="col-12 line-height-20" v-html="user.parseUsername(post.user)"></div>
            <div class="col-12">
                <div class="row justify-content-center">
                <Image :src="post.user.avatar" :alt="post.user.username" :size="[75, 75]" rounded />
            </div>
            </div>
            <div class="col-12" v-html="post.user.user_group.group_name"></div>
            <div class="col-12 row text-align-center">
                <div class="col-sm-6">
                    Posts:
                </div>
                <div class="col-sm-6">
                    {{  post.user.posts_no  }}
                </div>
            </div>
        </div>
        <div class="col-10">
            <div class="col-12 margin-x-m border-bottom-1">
                <div class="padding-x-m">
                {{ created_at }}
            </div>
            </div>
            <div class="margin-l" v-html="post.content"></div>
        </div>
    </div>
</template>