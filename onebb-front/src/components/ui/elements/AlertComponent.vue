<script setup lang="ts">
defineProps<{
  id: string
  type: 'alert-danger' | 'alert-info' | 'alert-warning' | 'alert-success'
  dismiss: Function
  timeout?: number
}>()
</script>

<template>
  <div :id="`alert-${id}`">
    <div :class="[`${type}-glow`]"></div>
    <div class="row" :class="[type]">
        <div v-if="timeout" class="col-12 margin-sm-bottom-m">
            <span class="alert-timeout" :style="`animation: alert-timeout-animation ${timeout}s linear;`"></span>
        </div>
        
        <div style="width: 160px;">
            <slot></slot>
        </div>
      
      <button
        @click="dismiss(id)"
        class="button button-color-white border-radius-circel row align-items-center justify-content-center"
        style="width: 30px; height: 30px"
      >
        <svg xmlns="http://www.w3.org/2000/svg" height="1rem" fill="white" viewBox="0 0 384 512">
          <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
          <path
            d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"
          />
        </svg>
      </button>
    </div>
  </div>
</template>
