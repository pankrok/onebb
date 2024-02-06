<script setup lang="ts">
import Alert from './AlertComponent.vue'
import useAlertStore from '@/stores/useAlertStore'

const alertStore = useAlertStore()


alertStore.setAlert({
    type: 'alert-info',
    message: 'INFO',
    timeout: 10,
})

</script>

<template>
  <Teleport to="body">
    <TransitionGroup name="alert" tag="div" class="alert-wrapper">
      <Alert
        v-for="alert in alertStore.alerts"
        :type="alert.type"
        :id="alert.id"
        :dismiss="alert.dismiss"
        :timeout="alert.timeout"
        :key="alert.id"
      >
        <span v-html="alert.message"></span>
      </Alert>
        </TransitionGroup>
  </Teleport>
</template>
