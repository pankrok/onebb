<script setup lang="ts">
import { defineProps } from 'vue';
import BoxHeaderComponent from './BoxHeaderComponent.vue';
import BoxFooterComponent from './BoxFooterComponent.vue';

const props = defineProps<{
    wrapperClass?: object|string[],
    boxClass?: object|string[],
    header?: boolean,
    footer?: boolean,
}>()
const defaultClass = [
    'border-1', 
    'border-color-dark', 
    'border-radius-5', 
    'background-white', 
    'color-black',
    "box-shadow-dark",
    'margin-s',
];

const classList = props.boxClass ?? defaultClass;
</script>

<template>
    <div :class="wrapperClass">
        <div class="" :class="classList">
            <BoxHeaderComponent v-if="header">
                <slot name="header"></slot>
            </BoxHeaderComponent>
            <slot></slot>
            <BoxFooterComponent v-if="footer">
                <slot name="footer"></slot>
            </BoxFooterComponent>
        </div>
    </div>
</template>