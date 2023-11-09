<template>
    <div class="editor" :class="{ active: isActive }">
      <textarea ref="$el"></textarea>
    </div>
  </template>
  
  <script setup lang="ts">
 //import { objectExpression } from "@babel/types";
 import 'jodit/es2021/jodit.min.css';
  import {
    ref,
    onMounted,
    onBeforeUnmount,
    onUnmounted,
    defineProps,
    defineEmits,
  } from "vue";
  import { Jodit } from 'jodit';
  
  const props = defineProps({
    value: { type: String, required: true },
    config: { type: Object, default: () => ({}) },
    plugins: { type: Array, default: () => [] },
  });
  
  const emit = defineEmits(["updateEvent"]);
  const isActive = ref(true);
  const editor = ref();
  const $el = ref(null);
  
  const loadJodit = async function () {
  
    // @ts-ignore
    editor.value = new Jodit($el.value, {theme: 'onebb', ...props.config});
    editor.value.value = props.value;
    editor.value.events.on("change", (newValue: string) => {
      emit("updateEvent", newValue);
    });
    isActive.value = true;
  };
  
  onMounted(() => {
    loadJodit();
  });
  
  onBeforeUnmount(() => {
    isActive.value = false;
  });
  
  onUnmounted(() => {
    editor.value.destruct();
  });
  </script>
  
  <style>
  .editor {
    opacity: 0;
    transition: opacity 0.1s ease-in-out;
  }
  
  .active {
    opacity: 1;
  }

.jodit_theme_onebb {
    --jd-color-background-default: #171721;
    --jd-color-border: #4e4e4e;
    --jd-color-panel: #252533;
    --jd-color-icon: #f3f3f3;
    --jd-color-text-icons: #f3f3f3;
    .jodit-ui-button__text, .jodit-toolbar-button__text { color: #f3f3f3; }
    .jodit-toolbar-button__trigger > svg {fill: #f3f3f3;}
}
</style>
