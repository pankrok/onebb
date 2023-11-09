<script setup lang="ts">
import { defineProps } from 'vue'
import type { IPost } from '@/interfaces'
import { parseUsername } from '@/hooks/helpers';
import Image from '@/components/ui/ImageComponent.vue'
import { useMoment } from '@/hooks/useMoment'

const props = defineProps<{ post: IPost, quote: boolean }>()
const { parse } = useMoment()
const created_at = parse(props.post.created_at)
</script>
<template>
  <div class="col-2">
    <div class="row display-mobile-none display-desktop-flex desktop-column align-items-center padding-m font-size-14">
      <span class="font-size-14" v-html="parseUsername(post.user)"></span>
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
    <div class="column justify-content-space-between font-size-14 height-12">
      <div class="row">
        <div class="col-sm-3 display-mobile-flex display-desktop-none justify-content-mobile-center">
          <Image
            :src="post.user.avatar"
            :alt="post.user.username"
            :size="[50, 50]"
            class="border-radius-circel img-size-mobile-m img-size-mobile-s padding-y-l"
          />
        </div>
        <div class="col-12 col-sm-9 column justify-content-mobile-center justify-content-flex-start">
          <div class="font-size-14 display-mobile-flex display-desktop-none" v-html="parseUsername(post.user)"></div>
          <div class="color-light padding-bottom-l">{{ created_at }}</div>
        </div>
        
      </div>
      
      <div class="col-auto color-white padding-top-s" v-html="post.content"></div>
      <div class="row justify-content-flex-end border-top-1 border-color-primary padding-y-s">
        <div>
          <button v-if="quote" class="button button-color-blue">Cytuj</button>
          <button class="button button-color-yellow">Zglos</button>
        </div>
      </div>
    </div>
  </div>
</template>
