<script setup lang="ts">
import { defineProps } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    callback: Function
}>();

</script>
<template>
    <span>
        <button @click="props.callback('SignIn')">{{ t('default.welcome back') }}</button>
    </span>
</template>