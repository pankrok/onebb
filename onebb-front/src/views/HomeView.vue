<script setup lang="ts">
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import { ICategory } from "@/interfaces/obbApiInterface";
import Category from "@/components/ui/partial/Category.vue";

const store = useStore();
const categories = computed<ICategory[]>(
  () => store.getters["obb/getData"]
);

onBeforeMount(() => {
  store.dispatch("setTitle", "Home");
  store.dispatch("obb/getCategory");
});

</script>

<template>
  <Category v-for="category in categories" :category="category" />
</template>
