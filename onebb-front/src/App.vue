<script setup lang="ts">
import { useRoute, RouterView } from 'vue-router'
import { useMessenger } from './hooks/obbClient'
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import MessengerComponent from '@/components/ui/partials/MessengerComponent.vue'
import useMessengerStore from '@/stores/useMessengerStore'
import { onMounted, onUnmounted, ref } from 'vue'
import useConfigStore from './stores/useConfigStore'
import { storeToRefs } from 'pinia'
import CustomBoxComponent from '@/components/ui/skinBoxes/CustomBoxComponent.vue'
import PluginBoxComponent from '@/components/ui/skinBoxes/PluginBoxComponent.vue'
import usePlugins, { initPlugins } from './utils/usePlugins'
import { watch } from 'vue'

const plugins = usePlugins()
//@ts-ignore
window.$obbPlugins = plugins

const messengerStore = useMessengerStore()
const messenger = useMessenger()
const route = useRoute()
const interval = ref()

const configStore = useConfigStore()
configStore.init()
const { pageBoxes } = storeToRefs(configStore)
console.log({ pageBoxes })
const boxComponents: { [index: string]: any } = {
  PluginBox: PluginBoxComponent,
  CustomBox: CustomBoxComponent
}

watch(route, () => {
  initPlugins(route.name?.toString())
})

onMounted(() => {
  interval.value = setInterval(() => {
    messenger.getNewChats()
  }, 5000)
  initPlugins(route.name?.toString())
})

onUnmounted(() => {
  clearInterval(interval.value)
})
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
      <aside v-if="pageBoxes?.top" class="col-12">
        <component
          v-for="box in pageBoxes.top"
          :is="boxComponents[box.engine]"
          :key="`plugin_box_${box.id}`"
          v-bind="box"
        />
      </aside>
      <aside v-if="pageBoxes?.left" class="col-3">
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
          <component :is="Component" :key="route.fullPath" />
        </Transition>
      </router-view>
      <aside v-if="pageBoxes?.right" class="col-3">
        <div class="margin-left-l">
          <component
            v-for="box in pageBoxes.right"
            :is="boxComponents[box.engine]"
            :key="`plugin_box_${box.id}`"
            v-bind="box"
          />
        </div>
      </aside>
      <aside v-if="pageBoxes?.bottom" class="col-12">
        <component
          v-for="box in pageBoxes.bottom"
          :is="boxComponents[box.engine]"
          :key="`plugin_box_${box.id}`"
          v-bind="box"
        />
      </aside>
    </div>
  </main>
  <Transition mode="in-out" name="fade">
    <MessengerComponent v-if="messengerStore.showMessenger" key="messenger-component" />
  </Transition>

  <FooterComponent />
</template>
