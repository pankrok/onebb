<script setup lang="ts">
import { usePage } from '@/hooks/obbClient';
import type { IPage } from '@/interfaces';
import { ref } from 'vue';

const page = ref<IPage|null>(null);

usePage().getPage().then(response => {
    page.value = response;
})

</script>

<template>
    <div class="col-12 border-1 background-primary border-color-dark margin-y-l" v-if="page">
    <section class="row">
        <div
              class="col-12 padding-sm-m border-bottom-1 background-stripes border-color-dark"
            >
      <h1 class="col-12-auto margin-sm-y-none">{{ page.name }}</h1>
      </div>
     <div class="col-12 padding-sm-s" v-html="page.content"></div>
    </section>
    </div>
</template>