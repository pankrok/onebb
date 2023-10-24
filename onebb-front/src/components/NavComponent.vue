<script setup lang="ts">
import { useUser } from '@/hooks/useUser'

import UserNavComponent from './ui/user/UserNavComponent.vue'
const { getUser, logout } = useUser()
const user = getUser()

defineProps<{
  loginToggle: Function
  registerToggle: Function
}>()
</script>
<template>
  <header>
    <nav class="background-primary border-color-primary padding-x-l padding-y-s box-shadow-primary">
      <ul>
        <li>
          <router-link
            :to="{
              name: 'Home'
            }"
          >
            <img
              class="img-size-m img-size-mobile-s"
              src="data:image/png;base64,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"
              alt="OneBB Logo"
            />
          </router-link>
        </li>
        <li>
          <a href="#">Link 1</a>
        </li>
        <li>
          <a href="#">Link 2</a>
        </li>
      </ul>
      <TransitionGroup tag="ul" name="list" mode="in-out">
        <li v-if="user.uid">
          <button class="button button-color-light position-relative">
            <span class="badge circle pulse background-red box-shadow-red"></span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="1.5em"
              fill="white"
              viewBox="0 0 448 512"
            >
              <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
              <title>Hello world!</title>
              <path
                d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"
              />
            </svg>
          </button>
        </li>
        <li v-if="user.uid">
          <button class="button button-color-light position-relative">
            <span class="circle pulse background-green box-shadow-green"></span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="1.5em"
              fill="white"
              viewBox="0 0 512 512"
            >
              <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
              <path
                d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"
              />
            </svg>
          </button>
        </li>

        <li v-if="user.uid">
          <button class="button button-color-light">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="1.5em"
              fill="white"
              viewBox="0 0 512 512"
            >
              <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
              <path
                d="M375.7 19.7c-1.5-8-6.9-14.7-14.4-17.8s-16.1-2.2-22.8 2.4L256 61.1 173.5 4.2c-6.7-4.6-15.3-5.5-22.8-2.4s-12.9 9.8-14.4 17.8l-18.1 98.5L19.7 136.3c-8 1.5-14.7 6.9-17.8 14.4s-2.2 16.1 2.4 22.8L61.1 256 4.2 338.5c-4.6 6.7-5.5 15.3-2.4 22.8s9.8 13 17.8 14.4l98.5 18.1 18.1 98.5c1.5 8 6.9 14.7 14.4 17.8s16.1 2.2 22.8-2.4L256 450.9l82.5 56.9c6.7 4.6 15.3 5.5 22.8 2.4s12.9-9.8 14.4-17.8l18.1-98.5 98.5-18.1c8-1.5 14.7-6.9 17.8-14.4s2.2-16.1-2.4-22.8L450.9 256l56.9-82.5c4.6-6.7 5.5-15.3 2.4-22.8s-9.8-12.9-17.8-14.4l-98.5-18.1L375.7 19.7zM269.6 110l65.6-45.2 14.4 78.3c1.8 9.8 9.5 17.5 19.3 19.3l78.3 14.4L402 242.4c-5.7 8.2-5.7 19 0 27.2l45.2 65.6-78.3 14.4c-9.8 1.8-17.5 9.5-19.3 19.3l-14.4 78.3L269.6 402c-8.2-5.7-19-5.7-27.2 0l-65.6 45.2-14.4-78.3c-1.8-9.8-9.5-17.5-19.3-19.3L64.8 335.2 110 269.6c5.7-8.2 5.7-19 0-27.2L64.8 176.8l78.3-14.4c9.8-1.8 17.5-9.5 19.3-19.3l14.4-78.3L242.4 110c8.2 5.7 19 5.7 27.2 0zM256 368a112 112 0 1 0 0-224 112 112 0 1 0 0 224zM192 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"
              />
            </svg>
          </button>
        </li>
        <li v-if="!user.uid">
          <button @click="registerToggle()" class="button button-color-light position-relative">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="1.5em"
              fill="white"
              viewBox="0 0 640 512"
            >
              <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
              <path
                d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
              />
            </svg>
          </button>
        </li>
        <li v-if="!user.uid">
          <button @click="loginToggle()" class="button button-color-light position-relative">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="1.5em"
              fill="white"
              viewBox="0 0 512 512"
            >
              <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
              <path
                d="M352 96l64 0c17.7 0 32 14.3 32 32l0 256c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0c53 0 96-43 96-96l0-256c0-53-43-96-96-96l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32zm-9.4 182.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L242.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"
              />
            </svg>
          </button>
        </li>
        <li class="cursor-pointer">
          <UserNavComponent :user="user" :logout="logout" />
        </li>
    </TransitionGroup>
    </nav>
  </header>
  <!-- <div
        class="col-12 border-bottom-1 border-color-blue background-color-dark row justify-content-space-between box-shadow-dark padding-y-l">
        <div class="col-3 aligne-items-center row">
            <router-link :to="{
                name: 'Home',
            }" class="font-size-18 font-weight-800 col-3">
                OneBB
            </router-link>
        </div>
        <div class="col-9 row justify-content-flex-end">
            <UserNavComponent :user="user" :logout="logout" />
            <div v-if="user.uid === 0" class="padding-x-m">
                <span 
                    
                    class="button cursor-pointer border-radius-10 text-align-center padding-s color-black"
                    @click="loginToggle()"
                >
                    Sign in
                </span>
                <span 
                    class="button cursor-pointer border-radius-10 text-align-center padding-s  color-black"
                    @click="registerToggle()"
                >
                    Sign up
                </span>
            </div>
        </div>
    </div> -->
</template>
