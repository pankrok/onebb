<script setup lang="ts">
import { defineProps } from 'vue'
import type { IPost } from '@/interfaces'
import { useUser } from '@/hooks/useUser'
import Image from '@/components/ui/ImageComponent.vue'
import { useMoment } from '@/hooks/useMoment'

const props = defineProps<{ post: IPost, noQuote: boolean }>()
const user = useUser()
const { parse } = useMoment()
const created_at = parse(props.post.created_at)
</script>
<template>
  <div class="col-2">
    <div class="column align-items-center padding-m font-size-14">
      <span class="font-size-14" v-html="user.parseUsername(post.user)"></span>
      <Image
        :src="post.user.avatar"
        :alt="post.user.username"
        :size="[100, 100]"
        class="border-radius-circel img-size-l img-size-mobile-s padding-y-l"
      />

      <div class="row col-12 justify-content-space-between">
        <span>Posts:</span><span>{{ post.user.posts_no }}</span>
      </div>
      <div class="row col-12 justify-content-space-between">
        <span>Plots:</span><span>{{ post.user.plots_no }}</span>
      </div>
    </div>
  </div>
  <div class="col-auto height-12">
    <div class="column justify-content-space-between margin-m padding-x-l font-size-14 height-12">
      <div class="color-light padding-bottom-l">{{ created_at }}</div>
      <div class="col-auto color-white padding-top-s" v-html="post.content"></div>
      <div class="row justify-content-flex-end border-top-1 border-color-primary padding-y-s">
        <div>
          <button v-if="!noQuote" class="button button-color-blue">Cytuj</button>
          <button class="button button-color-yellow">Zglos</button>
        </div>
      </div>
    </div>
  </div>
</template>
