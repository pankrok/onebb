<script setup lang="ts">
    defineProps<{
        teleport: string
        top?: string
        right?: string
    }>();
</script>
<template>

    <Teleport :to="teleport">
        <div class="position-absolute" :style="{top: top ?? '0px', right: right ?? 0}">
            <slot></slot>
        </div>
    </Teleport>

</template>