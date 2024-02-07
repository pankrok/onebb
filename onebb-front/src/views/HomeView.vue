<script setup lang="ts">
import CategoryComponent from '@/components/ui/partials/CategoryComponent.vue'
import { useCategory } from '@/hooks/obbClient'
import type { ICategory, IHydra } from '@/interfaces'
import instanceOf from '@/utils/instanceOf';
import { ref } from 'vue'

const categories = ref<ICategory[] | null>(null)
useCategory().then((response) => {
  if (instanceOf<ICategory[]>(response))
    categories.value = response
})

console.log('HOME', {categories: categories.value})
</script>

<template>
  <section class="row col-auto" key="homepage">
    <CategoryComponent v-for="(category, index) in categories" :key="category.id" :category="category" :index-no="index+1" />
  </section>
</template>
