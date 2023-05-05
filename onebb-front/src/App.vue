<script setup lang="ts">
import useCategory from '@/hooks/useCategory';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import Nav from './components/NavComponent.vue';

const route = useRoute();
const home = ref();
home.value = useCategory();

</script>

<template>
  <Nav />
  <main class="container">
    <router-view v-slot="{ Component }" :key="route.fullPath">
      <Transition name="fade" mode="out-in">
        <component :is="Component" />
      </Transition>
    </router-view>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </main>
</template>
