<script setup lang="ts">
import { useToast } from '@/hooks/useToast';

const alerts = useToast().getAlerts();

const types = {
    info: ['background-blue', 'border-color-blue', 'box-shadow-blue'],
    alert: ['background-red', 'border-color-red', 'box-shadow-red'],
    warning: ['background-yellow', 'border-color-yellow', 'box-shadow-yellow'],
    success: ['background-green', 'border-color-green', 'box-shadow-green'],
}

</script>
<template>
    <div class="position-absolute" style="z-index: 100000;">
        <TransitionGroup tag="div" name="alert" class="column position-relative margin-x-l" mode="in-out">
        <div v-for="alert, i in alerts" :key="alert.name + ' ' + i">
            <div class="margin-m padding-m border-1 border-color-dark border-radius-5 color-white" :class="types[alert.type]">
                {{ alert.name }}<br />
                {{ alert.text }}<br />
                {{ alert.type }}
            </div>
        </div>
        </TransitionGroup>
    </div>
</template>