<template>
<label v-if="cfg.label" :for="cfg.name" :class="cfg.labelClass">{{ cfg.label }}</label>
  <textarea
        :id="cfg.name"
        :name="cfg.name" 
        :class="cfg.class" 
  ></textarea>

</template>

<script>
import 'jodit/build/jodit.min.css'
import { Jodit } from 'jodit'
export default {
  name: 'Jodit',
  props: {
    cfg: null
  },
  data: () => ({ 
    editor: null,
    value: null,
    buttons: null,
    config: {},
    plugins: []
    }),
  computed: {
    editorConfig () {
      const config = { ...this.config }
      if (this.buttons) {
        config.buttons = this.buttons
        config.buttonsMD = this.buttons
        config.buttonsSM = this.buttons
        config.buttonsXS = this.buttons
      }
      if (this.extraButtons) config.extraButtons = this.extraButtons
      return config
    }
  },
  watch: {
    value (newValue) {
      if (this.editor.value !== newValue) {
        this.editor.value = newValue;
        }
      }
  },
  methods: {
    setDefaults() {
        this.value      = this.cfg.val      ?? '';
        this.buttons    = this.cfg.buttons  ?? null;
        this.config     = this.cfg.config   ?? {},
        this.plugins    = this.cfg.plugins  ?? []
    }
  },
  mounted () {
    this.setDefaults();
    
    if (this.plugins.length) {
      this.plugins.forEach(plugin => {
        Jodit.plugins.add(plugin.name, plugin.callback)
      })
    }
    this.editor = new Jodit(document.getElementById(this.cfg.name), this.editorConfig)
    this.editor.value = this.value
    this.editor.events.on('change', newValue => {this.$emit('update:formEl', {name: this.cfg.name, val: newValue})})
  },
  beforeUnmount () {
    this.editor.destruct()
  },
  emits: ['update:formEl'],
}
</script>
