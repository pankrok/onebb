<script setup lang="ts">
import { defineProps } from "vue";

defineProps<{
  boxes: number;
}>();
</script>

<template>
  <div v-for="n in boxes" class="box my-1" :key="n">
    <div class="box-header skeleton-header">
      <h2><span class="skeleton-box" style="width: 55%"></span></h2>
    </div>
    <div class="box-content">
      <ul class="list">
        <li class="list-item">
          <div class="content f-grow">
            <h3><span class="skeleton-box" style="width: 55%"></span></h3>
            <span class="skeleton-box" style="width: 80%"></span>
          </div>
          <div class="row col-2 text-right d-sm-none">
            <strong
              ><span class="skeleton-box" style="width: 60%"></span
            ></strong>
          </div>
          <div class="autor col-sm-3">
            <div class="px-xl-1 m-0">
              <div class="d-sm-none">
                <span class="skeleton-box" style="width: 60px"></span>
              </div>
              <div class="text-center">
                <span class="skeleton-box" style="width: 40px"></span>
              </div>
            </div>
            <span
              class="skeleton-box"
              style="width: 50px; height: 50px; border-radius: 50%"
            ></span>
          </div>
        </li>
        <li class="list-item">
          <div class="content f-grow">
            <h3><span class="skeleton-box" style="width: 55%"></span></h3>
            <span class="skeleton-box" style="width: 80%"></span>
          </div>
          <div class="row col-2 text-right d-sm-none">
            <strong
              ><span class="skeleton-box" style="width: 60%"></span
            ></strong>
          </div>
          <div class="autor col-sm-3">
            <div class="px-xl-1 m-0">
              <div class="d-sm-none">
                <span class="skeleton-box" style="width: 60px"></span>
              </div>
              <div class="text-center">
                <span class="skeleton-box" style="width: 40px"></span>
              </div>
            </div>
            <span
              class="skeleton-box"
              style="width: 50px; height: 50px; border-radius: 50%"
            ></span>
          </div>
        </li>
        <li class="list-item">
          <div class="content f-grow">
            <h3><span class="skeleton-box" style="width: 55%"></span></h3>
            <span class="skeleton-box" style="width: 80%"></span>
          </div>
          <div class="row col-2 text-right d-sm-none">
            <strong
              ><span class="skeleton-box" style="width: 60%"></span
            ></strong>
          </div>
          <div class="autor col-sm-3">
            <div class="px-xl-1 m-0">
              <div class="d-sm-none">
                <span class="skeleton-box" style="width: 60px"></span>
              </div>
              <div class="text-center">
                <span class="skeleton-box" style="width: 40px"></span>
              </div>
            </div>
            <span
              class="skeleton-box"
              style="width: 50px; height: 50px; border-radius: 50%"
            ></span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
.skeleton-header {
  background-color: var(--light);
}

.skeleton-box {
  display: inline-block;
  height: 1em;
  position: relative;
  overflow: hidden;
  background-color: var(--dark);
}
.skeleton-box::after {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  transform: translateX(-100%);
  background-image: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0) 0,
    rgba(255, 255, 255, 0.2) 20%,
    rgba(255, 255, 255, 0.5) 60%,
    rgba(255, 255, 255, 0)
  );
  animation: shimmer 2s infinite;
  content: "";
}
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}
</style>
