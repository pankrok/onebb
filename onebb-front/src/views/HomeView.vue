<script setup lang="ts">
import CategoryComponent from '@/components/ui/partials/CategoryComponent.vue'
import { useCategory } from '@/hooks/obbClient'
import type { ICategory, IHydra } from '@/interfaces'
import useTimelineStore from '@/stores/useTimelineStore';
import instanceOf from '@/utils/instanceOf';
import { ref } from 'vue'

const {getCategoryTimeline} = useTimelineStore();
const categories = ref<ICategory[] | null>(null)
useCategory().then((response) => {
  if (instanceOf<ICategory[]>(response))
    categories.value = response
})


</script>

<template>
  <section class="row col-auto" key="homepage">
    <CategoryComponent v-for="(category, index) in categories" :key="category.id" :category="category" :index-no="index+1" :is-read="getCategoryTimeline(category.id, category.updated_at)" />
  </section>
</template>
