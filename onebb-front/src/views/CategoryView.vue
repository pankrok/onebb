<script setup lang="ts">
import CategoryComponent from '@/components/ui/partials/CategoryComponent.vue'
import { useCategory } from '@/hooks/obbClient'
import type { ICategory, IHydra } from '@/interfaces'
import instanceOf from '@/utils/instanceOf';
import { ref } from 'vue'
import {$t} from '@/utils/i18n'

const categories = ref<ICategory[] | null>(null)
const categoryName = ref('');
useCategory().then((response) => {
  console.log({ response })
  if(instanceOf<ICategory[]>(response)) {
    categories.value = response;
    categoryName.value = response[0].name;
    categories.value[0].name = $t('board');
  }
  
})
</script>

<template>
  <section class="row col-auto">
    <h1 class="margin-s">
        {{ categoryName }}
    </h1>
    <CategoryComponent v-for="category in categories" :key="category.id" :category="category" />
  </section>
</template>
