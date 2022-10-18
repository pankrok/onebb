<template>

    <label v-if="cfg.label" :for="cfg.name" :class="cfg.labelClass">{{ cfg.label }}</label>
    <textarea 
        :id="cfg.name"
        :name="cfg.name" 
        :type="cfg.type" 
        :class="cfg.class" 
        :disabled="cfg.disabled"
        v-model="inputVal"
        @input="sendInput"
        @keyup.enter="$emit('update:formEl', {name: 'submit'})"

    >
    </textarea>
 
</template>
<script>
export default {
  name: 'Textarea',
  props: {
    cfg: Object
  },
  data() {
    return{
        inputVal: this.cfg.val ?? null,
    }
  },
  methods: {
    sendInput: function() {
        if (this.cfg.type === 'number') {
            this.inputVal = parseFloat(this.inputVal);
        }
    
        this.$emit('update:formEl', {name: this.cfg.name, val: this.inputVal})
    }
  },
  mounted() {
    this.$emit('update:formEl', {name: this.cfg.name, val: this.inputVal});
  },
  emits: ['update:formEl']
}
</script>