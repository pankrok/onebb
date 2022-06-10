<template>

    <label v-if="cfg.label" :for="cfg.name" :class="cfg.labelClass">{{ cfg.label }}</label>

    <select 
        :id="cfg.name"
        :name="cfg.name" 
        :class="cfg.class"
        :multiple="cfg.multiple"
        :style="cfg.styles"
        @change="sendInput"
        v-model="selected"
     >
      <option v-for="v in cfg.options" :value="v.val" :key="v.val" :selected="v.selected">{{ v.name }}</option>

    </select> 
   
</template>
<script>
export default {
  name: 'Select',
  props: {
    cfg: Object
  },
  data() {
    return {
        selected: this.cfg.val ?? null,
    }
  },
  methods: {
    sendInput: function() {     
        this.$emit('update:formEl', {name: this.cfg.name, val: this.selected})
    }
  },
  mounted() {
    this.$emit('update:formEl', {name: this.cfg.name, val: this.selected})
  },
  emits:['update:formEl']
  
}
</script>