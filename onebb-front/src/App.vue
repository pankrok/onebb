<script setup lang="ts">
import useCategory from '@/hooks/useCategory';
import { ref, defineAsyncComponent  } from 'vue';
import { useRoute } from 'vue-router';
import useLoading from '@/hooks/useLoading'

const Nav = defineAsyncComponent(() =>
  import('./components/NavComponent.vue')
)
const LoginModalComponent = defineAsyncComponent(() =>
  import('./components/ui/modals/LoginModalComponent.vue')
)
const RegisterModalComponent = defineAsyncComponent(() =>
  import('./components/ui/modals/RegisterModalComponent.vue')
)

const Alert = defineAsyncComponent(() =>
  import('./components/ui/AlertComponent.vue')
)
const {isLoading} = useLoading();
const route = useRoute();
const home = ref();
const login = ref(false)
const register = ref(false)

const loginToggle = () => {
  login.value = !login.value;
}

const registerToggle = () => {
  register.value = !register.value;
}

home.value = useCategory();

</script>

<template>
  <!-- <div class="mode staging">!!STAGING MODE!!</div> -->
  <Transition name="fade" mode="out-in">
    <LoginModalComponent v-if="login" key="loginBox" :loginToggle="loginToggle" />  
  </Transition>
  <Transition name="fade" mode="out-in">
    <RegisterModalComponent v-if="register" key="registerBox" :toggleModal="registerToggle" /> 
  </Transition>
  
  <Nav :loginToggle="loginToggle" :registerToggle="registerToggle" />
  <div class="position-asolute">
    <Alert />
  </div>
  <Transition name="fade" mode="out-in">
    <div v-if="isLoading" class="col-12 position-relative" key="loading-bar"><span class="box-loader box-shadow-blue"></span></div>
  </Transition>
  <main class="container margin-top-m">
    <router-view v-slot="{ Component }">
      <Transition name="fade" mode="out-in">
        <component :is="Component" :key="route.fullPath" />
      </Transition>
    </router-view>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </main>
  
</template>

<style>
.mode {
   position: fixed;
    top: 0;
    right: 0;
    padding: .25rem 2.5rem;
    border-radius: 0 0 0 5px;
    opacity: 0.7;
    font-size: 18px;
    font-weight: 900;
    z-index: 10000;
}

.staging {
  color: rgb(0, 0, 0);
  background-color: #ffbc00;
  box-shadow: 0px 0px 10px #ffbc00;
}

</style>