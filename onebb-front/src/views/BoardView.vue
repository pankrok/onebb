<script setup lang="ts">
import { useRoute, RouteRecordName } from "vue-router";
import { ref, computed } from "vue";
import BoardComponent from "@/components/ui/partial/Board.vue";

const route = useRoute();
const boardKey = ref<RouteRecordName>("");

boardKey.value = route.name ?? '';

const board = computed(() => {
  
})

</script>

<template>
  <div class="f-grow" :key="boardKey">
    <BoardComponent :boxes="1" :loading="loading" :board="board" />
  </div>
</template>
