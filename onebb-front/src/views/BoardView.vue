<script setup lang="ts">
import { useRoute, RouteRecordName } from "vue-router";
import { useStore } from "vuex";
import { ref, computed, onBeforeMount } from "vue";
import BoardComponent from "@/components/ui/partial/Board.vue";
import { IBoard } from "@/interfaces/obbApiInterface";

const store = useStore();
const route = useRoute();
const routeName = ref<RouteRecordName>("");
const loading = ref<boolean>(false);

routeName.value = route.name ?? "";
store.dispatch("obb/getBoard", route.params.id);

const board = computed<IBoard>(() => store.getters["obb/getData"]);
</script>

<template>
  <div class="f-grow" :key="routeName">
    <BoardComponent :boxes="1" :loading="loading" :board="board" />
  </div>
</template>
