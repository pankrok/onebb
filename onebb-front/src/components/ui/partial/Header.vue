<script setup lang="ts"></script>
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
        <ul class="second_menu">
          <li class="list-complete-item" style="">
            <button id="theme-toggle" class="btn btn-secondary">
              <i class="fa-sun fa-moon fas"></i>
            </button>
          </li>
          <li class="list-complete-item">
            <a href="#" class="px-1"
              ><i class="fas fa-sign-in-alt fa-lg"></i>
              <span class="d-sm-none">Sign in</span></a
            >
          </li>
          <li class="list-complete-item">
            <a href="/auth/signup" class="px-1"
              ><i class="fas fa-user-plus fa-lg"></i>
              <span class="d-sm-none">Sign up</span></a
            >
          </li>
          <li id="menuToggle" class="d-sm-block">
            <span></span><span></span><span></span>
          </li>
        </ul>
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
  </nav>
</template>
