<template>
    <div v-html="content">
    </div>
</template>

<script>
export default {
  name: 'PluginBox',
  props: {
    name: String,
    content: String,
  }
}
</script>

