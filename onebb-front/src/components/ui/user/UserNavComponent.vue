<script setup lang="ts">
import ImageComponent from '../ImageComponent.vue'
import type { ITokenResponse } from '@/interfaces'
import BoxComponent from '../../box/BoxComponent.vue'
import { ref } from 'vue'

defineProps<{
  user: ITokenResponse
  logout: Function
}>()

const menu = ref(false)
</script>
<template>
  <div v-if="user.uid !== 0" class="row aligne-items-center justify-content-center padding-x-m">
    <ImageComponent
      :size="[25, 25]"
      :src="user.avatar"
      :alt="user.slug"
      :class="['img-size-mobile-s', 'border-radius-circel']"
      @click="menu = !menu"
    />
    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="white" viewBox="0 0 320 512">
            <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
            <path
              d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"
            />
    </svg>
    <Transition name="slide-fade-right" mode="out-in">
      <BoxComponent v-if="menu"  key="main-menu" :wrapperClass="['position-fixed', 'sidemenu']">
        <ul class="column padding-l">
           <li class="pointer">
            <RouterLink
                :to="{
                    name: 'Profile',
                    params: {
                    slug: user.slug,
                    id: user.uid
                    } 
                }"
                >
                    {{ user.slug }}
                </RouterLink>
            </li>
          <li class="pointer" @click="logout()">Logout</li>
        </ul>
      </BoxComponent>
    </Transition>
    
  </div>
</template>

