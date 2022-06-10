<template>
<div class="row">
<div 
    class="onoffswitch mx-1" 
    @click="$emit('update:formEl', {name: $event.target.name, val: !isChecked})"
>
  <input  
        :id="cfg.name"
        :name="cfg.name" 
        :class="cfg.class" 
        type="checkbox" 
        class="onoffswitch-checkbox" 
        tabindex="0"
        v-model="isChecked"
        >
  
  <label class="onoffswitch-label" :for="cfg.name">
  </label>
</div>
<div v-if="cfg.label" :class="cfg.labelClass">
    {{ cfg.label }}
</div>
</div>

</template>
<script>
export default {
  name: 'Checkbox',
  props: {
    cfg: Object
  },
  data() {
    return {
        isChecked: this.cfg.checked ?? false,
    }
  },
  mounted() {
    this.$emit('update:formEl', {name: this.cfg.name, val: this.isChecked});
  },
  emits: ['update:formEl']
}
</script>