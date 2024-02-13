<script setup lang="ts">
import { useRoute, RouterView } from 'vue-router'
import { useMessenger } from './hooks/obbClient'
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import useMessengerStore from '@/stores/useMessengerStore'
import { onMounted, onUnmounted, ref } from 'vue'
import useConfigStore from './stores/useConfigStore'
import { storeToRefs } from 'pinia'
import { defineAsyncComponent } from 'vue'
import usePlugins, { initPlugins } from './utils/usePlugins'
import { watch } from 'vue'
import useUserStore from './stores/useUserStore'
import useLoadingStore from './stores/useLoadingStore'

const MessengerComponent = defineAsyncComponent(
  () => import('@/components/ui/partials/MessengerComponent.vue')
)

const AlertWrapper = defineAsyncComponent(
  () => import('@/components/ui/elements/AlertWrapperComponent.vue')
)

const plugins = usePlugins()
//@ts-ignore
window.$obbPlugins = plugins

const {loading} = storeToRefs(useLoadingStore())
const messengerStore = useMessengerStore()
const messenger = useMessenger()
const route = useRoute()
const interval = ref<number|undefined>(undefined)

const configStore = useConfigStore()
const { logged } = storeToRefs(useUserStore())
configStore.init()
const { pageBoxes } = storeToRefs(configStore)
console.log({ pageBoxes })
const boxComponents: { [index: string]: any } = {
  PluginBox: defineAsyncComponent(() => import('@/components/ui/skinBoxes/PluginBoxComponent.vue')),
  CustomBox: defineAsyncComponent(() => import('@/components/ui/skinBoxes/CustomBoxComponent.vue')),
  UserStatistics: defineAsyncComponent(
    () => import('@/components/ui/skinBoxes/UserStatisticBoxComponent.vue')
  )
}
console.log({ AppRoute: route.fullPath.toString() })
watch(route, () => {
  initPlugins(route.name?.toString())
})

watch(logged, () => {
  console.log({interval: interval.value})
  
  if (!interval.value && logged.value) {
    interval.value = setInterval(() => {
      messenger.getNewChats()
    }, 1000 * Number(import.meta.env.VITE_APP_MESSENGER_DIFF))
  }

  if (interval.value && !logged.value) {
    clearInterval(interval.value)
  }
})

onMounted(() => {
  if (!interval.value && logged.value) {
    interval.value = setInterval(() => {
      messenger.getNewChats()
    }, 1000 * Number(import.meta.env.VITE_APP_MESSENGER_DIFF))
  }
  initPlugins(route.name?.toString())
})

onUnmounted(() => {
  clearInterval(interval.value)
})
</script>

<template>
  <HeaderComponent />
  <main class="container margin-top-l padding-sm-x-m">
    <Transition name="fade" mode="in-out">
      <span v-if="loading" class="position-fixed box-loader col-12" key="main-loader"></span>
    </Transition>
    
    <!-- <section class="col-12">
      <div class="navigation row align-items-center border-none bfont-size-12">
        <a href="#" class="padding-right-m">Home</a> >
        <a href="#" class="padding-m active">Home</a>
      </div>
    </section> -->
    <TransitionGroup class="row position-relative" name="list" tag="div">
      <aside v-if="pageBoxes?.top" class="col-12" key="top-modules">
        <component
          v-for="box in pageBoxes.top"
          :is="boxComponents[box.engine]"
          :key="`plugin_box_${box.id}`"
          v-bind="box"
        />
      </aside>
      <aside v-if="pageBoxes?.left" class="col-3" key="left-modules">
        <div class="margin-right-l">
          <component
            v-for="box in pageBoxes.left"
            :is="boxComponents[box.engine]"
            :key="`plugin_box_${box.id}`"
            v-bind="box"
          />
        </div>
      </aside>
      <router-view v-slot="{ Component }">
        <Transition name="fade" mode="out-in">
          <component :is="Component" :key="route.fullPath.toString()" />
        </Transition>
      </router-view>
      <aside v-if="pageBoxes?.right" class="col-3" key="right-modules">
        <div class="margin-left-l">
          <component
            v-for="box in pageBoxes.right"
            :is="boxComponents[box.engine]"
            :key="`plugin_box_${box.id}`"
            v-bind="box"
          />
        </div>
      </aside>
      <aside v-if="pageBoxes?.bottom" class="col-12" key="bottom-modules">
        <component
          v-for="box in pageBoxes.bottom"
          :is="boxComponents[box.engine]"
          :key="`plugin_box_${box.id}`"
          v-bind="box"
        />
      </aside>
    </TransitionGroup>
  </main>
 
    <MessengerComponent :is-active="messengerStore.showMessenger" key="messenger-component" />

  <AlertWrapper />

  <FooterComponent />
</template>
<style>
@import '/public/main.css';
</style>
