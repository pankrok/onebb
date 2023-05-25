<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const props = defineProps<{next: boolean}>()

const route = useRoute();
const router = useRouter();
const disablePrev = computed(()=>( Number(route.params.page) <= 1))
const disableNext = computed(()=>( props.next))

</script>
<template>
<div class="row justify-content-space-between margin-l">
    <div>
        <router-link :to="{params: {page: `${Number(route.params.page) -1}`}}" v-if="!disablePrev" class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m" :class="{'button-disabled': disablePrev}">Back</router-link>
    </div>
    <div>
        <router-link :to="{params: {page: `${Number(route.params.page) +1}`}}" v-if="!disableNext" class="button button-background-blue button-color-white border-radius-5 padding-x-s margin-bottom-m" :class="{'button-disabled': disableNext}">Next</router-link>
    </div>
</div>

</template>
