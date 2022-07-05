<template>
    <button :name="cfg.name" :type="cfg.type" :class="cfg.class"  @click="$emit('update:formEl', {name: $event.target.name, val: $event.target.type, custom: cfg.custom ?? null})">{{ cfg.text }}</button>
</template>
<script>
export default {
  name: 'Button',
  props: {
    cfg: Object,
  },
  emits:['update:formEl']
}
</script>