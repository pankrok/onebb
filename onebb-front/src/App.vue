<script setup lang="ts">
import { ref, defineAsyncComponent, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import useLoadingStore from '@/stores/useLoadingStore'
import useSkin from './hooks/useSkin'
import CustomBoxComponent from './components/custom_boxes/CustomBoxComponent.vue'
import PluginBoxComponent from './components/custom_boxes/PluginBoxComponent.vue'
import { storeToRefs } from 'pinia'

const Nav = defineAsyncComponent(() => import('./components/NavComponent.vue'))
const LoginModalComponent = defineAsyncComponent(
  () => import('./components/ui/modals/LoginModalComponent.vue')
)
const RegisterModalComponent = defineAsyncComponent(
  () => import('./components/ui/modals/RegisterModalComponent.vue')
)

const Alert = defineAsyncComponent(() => import('./components/ui/AlertComponent.vue'))
const loadingStore = useLoadingStore()
const { loading } = storeToRefs(loadingStore)
const route = useRoute()
const login = ref(false)
const register = ref(false)
const boxes = ref(null)
const boxComponents = {
  PluginBox: PluginBoxComponent,
  CustomBox: CustomBoxComponent
}

const loginToggle = () => {
  login.value = !login.value
}

const registerToggle = () => {
  register.value = !register.value
}

onMounted(async () => {
  const handler = await useSkin()
  console.log({ handler })
  // @ts-ignore
  boxes.value = handler?.Home
  console.log({ handler: boxes.value })
})
</script>

<template>
  <div class="mode staging">!!STAGING MODE!!</div>

  <Transition name="fade" mode="out-in">
    <LoginModalComponent v-if="login" key="loginBox" :loginToggle="loginToggle" />
  </Transition>
  <Transition name="fade" mode="out-in">
    <RegisterModalComponent v-if="register" key="registerBox" :toggleModal="registerToggle" />
  </Transition>

  <Nav :loginToggle="loginToggle" :registerToggle="registerToggle" />

  <Transition name="fade" mode="out-in">
    <div v-if="loading" key="loading-bar">
      <span class="position-fixed box-loader col-12"></span>
    </div>
  </Transition>
  <main class="container margin-top-l">
    <!-- breadcrumbs section -->
    <section class="col-12">
      <div
        class="row margin-x-m align-items-center border-1 border-radius-5 border-color-primary font-size-14"
      >
        <a href="#" class="padding-m">Home</a> > <a href="#" class="padding-m">Home</a> >
        <a href="#" class="padding-m">Home</a> > <a href="#" class="padding-m active">Home</a>
      </div>
    </section>
    <!-- /breadcrumbs section -->
    <!-- top modules section -->
    <aside v-if="boxes?.top" class="col-12">
      <div v-for="box in boxes.top" :key="box.name">
        <component :is="boxComponents[box.engine]" :inner-html="box.html" />
      </div>
    </aside>
    <!-- /top modules section -->
    <div class="row col-12">
      <aside v-if="boxes?.left" class="col-3">
        <div v-for="box in boxes.left" :key="box.name">
          {{ box }}
          <!-- <component :is="box.engine" :name="box.name" :content="box.html" /> -->
        </div>
      </aside>
      <section class="col-auto column">
        <router-view v-slot="{ Component }">
          <Transition name="fade" mode="out-in">
            <component :is="Component" :key="route.fullPath" />
          </Transition>
        </router-view>
      </section>
      <aside v-if="boxes?.left" class="col-3">
        <div v-for="box in boxes.left" :key="box.name">
          {{ box }}
          <!-- <component :is="box.engine" :name="box.name" :content="box.html" /> -->
        </div>
      </aside>
    </div>
    <aside v-if="boxes?.bottom" class="col-12">
      <div v-for="box in boxes.bottom" :key="box.name">
        {{ box }}
        <!-- <component :is="box.engine" :name="box.name" :content="box.html" /> -->
      </div>
    </aside>
    <aside style="position: fixed; right: 5%; bottom: 15%; z-index: 25">
      <div class="col-12 column align-items-center position-relative">
        <Alert />
      </div>
    </aside>
  </main>
</template>

<style>
.mode {
  position: fixed;
  top: 0;
  right: 0;
  padding: 0.05rem 0.5rem;
  border-radius: 0 0 0 5px;
  opacity: 0.7;
  font-size: 12px;
  font-weight: 900;
  z-index: 10000;
}

.staging {
  color: rgb(0, 0, 0);
  background-color: #ffbc00;
  box-shadow: 0px 0px 10px #ffbc00;
}
</style>
