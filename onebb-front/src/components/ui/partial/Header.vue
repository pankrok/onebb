<script setup lang="ts">
import { ref } from "vue";
import { useStore } from "vuex";
import { ISignIn } from "@/interfaces/FormInterfaces";
import Modal from "@/components/ui/elements/Modal.vue";
import SignIn from "@/components/ui/partial/SignIn.vue";

const signInModal = ref<boolean>(false);
const navDropdown = ref<boolean>(false);
const store = useStore();
const signInButton = () => {
  signInModal.value = !signInModal.value;
};

const logout = () => {
  store.dispatch("user/logout");
}

const signInProps = ref({
  logging: false,
  error: false,
  success: false,
  callback: (form: ISignIn) => {
    signInProps.value.logging = true;
    store.dispatch("user/login", form)
    .then(code => {
      if (code === 200) {
        signInProps.value.success = true;
        setTimeout(()=>{
          signInProps.value.logging = false;
          signInProps.value.success = false;
          signInProps.value.hideModal();
        }, 1500)
      } else {
        signInProps.value.error = true;
        setTimeout(()=>{
          signInProps.value.logging = false;
          signInProps.value.error = false;
        }, 1500)
      }
    });
  },
  hideModal: () => {
    signInModal.value = false;
  }
});
</script>
<template>
  <nav>
    <div class="header">
      <div class="header-wrapper">
        <div class="logo">
          <router-link
            :to="{
              name: 'Home',
            }"
          >
            <img
              class="logo-img"
              src="http://bdev.s89.eu/skins/standard/img/logo.png"
            />
          </router-link>
        </div>
        <TransitionGroup
          name="list-complete"
          tag="ul"
          class="second_menu"
          mode="out-in"
        >
          <li class="list-complete-item" style="">
            <button id="theme-toggle" class="btn btn-secondary">
              <i class="fa-sun fa-moon fas"></i>
            </button>
          </li>
          <li v-if="!store.state.user.loggedIn" class="list-complete-item">
            <a @click="signInButton" href="#" class="px-1"
              ><i class="fas fa-sign-in-alt fa-lg"></i>
              <span class="d-sm-none">Sign in</span></a
            >
          </li>
          <li v-if="!store.state.user.loggedIn" class="list-complete-item">
            <a href="/auth/signup" class="px-1"
              ><i class="fas fa-user-plus fa-lg"></i>
              <span class="d-sm-none">Sign up</span></a
            >
          </li>
          <li
            @click="navDropdown = !navDropdown"
            class="list-complete-item dropdown-nav"
            v-if="store.state.user.loggedIn"
          >
            <img
              :src="store.state.baseUrl + store.state.user.avatar"
              alt="Avatar"
              class="avatar xs mx-1"
            /><i class="fa-solid fa-caret-down"></i>
            <Transition name="slide-fade">
              <ul v-if="navDropdown" class="dropdown">
                <li class="dropdown-item" v-if="store.state.user.loggedIn">
                  <router-link
                    class="px-1"
                    :to="{
                      name: 'Profile',
                      params: {
                        slug: store.state.user.slug,
                        id: store.state.user.uid,
                      },
                    }"
                    ><i class="fa-solid fa-user"></i>
                    <span>{{ $t("profile") }}</span>
                  </router-link>
                </li>
                <li class="dropdown-item" v-if="store.state.user.loggedIn">
                  <router-link
                    class="px-1"
                    :to="{
                      name: 'Profile', // here should be UserConfig route!
                      params: { id: store.state.user.uid, slug:  store.state.user.slug, },
                    }"
                    ><i class="fa-solid fa-cog"></i>
                    <span>{{ $t("configuration") }}</span>
                  </router-link>
                </li>
                <li class="dropwodn-item header"></li>
                <li class="dropdown-item" v-if="store.state.user.loggedIn">
                  <a href="#" class="px-1" @click.stop.prevent="logout"
                    ><i class="fa fa-sign-out fa-lg"></i>
                    <span>{{ $t("logout") }}</span></a
                  >
                </li>
              </ul>
            </Transition>
          </li>
          <li id="menuToggle" class="d-sm-block">
            <span></span><span></span><span></span>
          </li>
        </TransitionGroup>
      </div>
    </div>
    <div id="menu" class="header second">
      <div class="header-wrapper">
        <ul class="menu">
          <li>
            <router-link
              :to="{
                name: 'Home',
              }"
            >
              Home</router-link
            >
          </li>
          <li>
            <a href="/i/meta-page/1" class=""><span>meta page</span></a>
          </li>
        </ul>
      </div>
    </div>
    <Modal :show="signInModal">
      <SignIn v-bind="signInProps"/>
    </Modal>
  </nav>
</template>
