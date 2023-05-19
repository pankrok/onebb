<script setup lang="ts">
import { useRoute } from 'vue-router';
const route = useRoute();

const s = ()=>{
    console.log({route})
}

</script>
<template>
<div class="row justify-content-space-between">
    <div>
        <button class="button button-primary">Back</button>
    </div>
    <div>
        <button class="button button-primary" @click="s()">Next</button>
    </div>
</div>

</template>
