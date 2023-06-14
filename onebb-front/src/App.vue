<script setup lang="ts">
import useCategory from '@/hooks/useCategory';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import Nav from './components/NavComponent.vue';
import LoginBoxComponent from './components/ui/modals/LoginBoxComponent.vue';

const route = useRoute();
const home = ref();
const login = ref(false)

const loginToggle = () => {
  login.value = !login.value;
}

home.value = useCategory();


</script>

<template>
  <Transition name="fade" mode="out-in">
    <LoginBoxComponent v-if="login" key="loginBox" :loginToggle="loginToggle" />
  </Transition>
  
  <Nav :loginToggle="loginToggle" />
  <main class="container margin-top-m">
    <router-view v-slot="{ Component }">
      <Transition name="fade" mode="out-in">
        <component :is="Component" :key="route.fullPath" />
      </Transition>
    </router-view>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </main>
</template>
