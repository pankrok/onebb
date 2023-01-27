<script setup lang="ts">
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { onBeforeUnmount, onBeforeMount, computed } from "vue";
import { ICategory } from "@/interfaces/obbApiInterface";
import Category from "@/components/ui/partial/Category.vue";

const store = useStore();
const route = useRoute();

const category = computed<ICategory>(() => store.getters["obb/getData"]);

onBeforeMount(() => {
  store.dispatch("setTitle", "Home");
  store.dispatch("obb/getCategory", route.params.id);
});

onBeforeUnmount(() => {
  store.dispatch('obb/clear');
})
</script>

<template>
  <Category :category="category" />
</template>
