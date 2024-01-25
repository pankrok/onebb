<script setup lang="ts">

import { useRoute, RouterView } from 'vue-router'
import { useSkin } from './hooks/obbClient'
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import MessengerComponent from '@/components/ui/partials/MessengerComponent.vue'
import useMessengerStore from '@/stores/useMessengerStore'

const messengerStore = useMessengerStore();
useSkin()
const route = useRoute()
</script>

<template>
  <HeaderComponent />
  <main class="container margin-top-l padding-sm-x-m">
    <!-- <span class="position-fixed box-loader col-1"></span> -->
    <!-- <section class="col-12">
      <div class="navigation row align-items-center border-none bfont-size-12">
        <a href="#" class="padding-right-m">Home</a> >
        <a href="#" class="padding-m active">Home</a>
      </div>
    </section> -->
    <div class="row">
      <!-- <aside class="col-3">
            <div class="margin-m padding-m border-color-red border-radius-5 box-shadow-red background-red color-white font-weight-600">
                This is alert
            </div>
        </aside> -->
        <router-view v-slot="{ Component }">
          <Transition name="fade" mode="out-in">
            <component :is="Component" :key="route.fullPath" />
          </Transition>
        </router-view>
      <!-- <aside class="col-3">
            <div class="margin-m padding-m border-color-primary border-radius-5 box-shadow-green background-green color-white font-weight-600">
                This is success
            </div>
        </aside> -->
    </div>
  </main>
  <Transition mode="in-out" name="fade">
    <MessengerComponent v-if="messengerStore.showMessenger" key="messenger-component" />
  </Transition>
  
  <FooterComponent />
</template>
