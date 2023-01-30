<script setup lang="ts">
import Header from '@/components/ui/partial/Header.vue';
import { computed } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router'
import PluginBox from '@/components/ui/elements/Box/PluginBox.vue';

const store = useStore();
const route = useRoute();
store.dispatch('boxes/get');

const boxComponents: any = {
  PluginBox,
}

const boxes = computed(() => {
  return store.state.boxes[(route.name ?? 'Home')];
})

</script>

<template>
  <main>
    <Header />
    <TransitionGroup
      name="list-complete"
      tag="div"
      class="container"
      mode="out-in"
    >
      <div class="col-12 list-complete-item" v-if="boxes.top.length > 0">
        <div v-for="box in boxes.top" :key="box.name">
          <component :is="boxComponents[box.engine]" :name="box.name" :content="box.html" />
        </div>
      </div>

      <div class="col-3 list-complete-item" v-if="boxes.left.length > 0">
        <div v-for="box in boxes.left" :key="box.name">
          <component :is="boxComponents[box.engine]" :name="box.name" :content="box.html" />
          
        </div>
      </div>

      <div class="f-grow list-complete-item p-relative" key="main">
        <router-view v-slot="{ Component }" :key="route.fullPath">
          <Transition name="fade" mode="out-in">
            <component :is="Component" />
          </Transition>
          
        </router-view>
      </div>

      <div class="col-3 list-complete-item" v-if="boxes.right.length > 0">
        <div v-for="box in boxes.right" :key="box.name">
          <component :is="boxComponents[box.engine]" :name="box.name" :content="box.html" />
        </div>
      </div>

      <div class="col-12 list-complete-item" v-if="boxes.bottom.length > 0">
        <div v-for="box in boxes.bottom" :key="box.name">
          <component :is="boxComponents[box.engine]" :name="box.name" :content="box.html" />
        </div>
      </div>
    </TransitionGroup>
    <!-- <Transition name="fade">
      <Messenger v-if="logged" />
    </Transition> -->
  </main>
</template>

<style>
@import "@/assets/base.css";
</style>
