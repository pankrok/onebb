<template>
    <div class="editor" :class="{ active: isActive }">
      <textarea ref="$el"></textarea>
    </div>
  </template>
  
  <script setup lang="ts">
 //import { objectExpression } from "@babel/types";
  
  import {
    ref,
    computed,
    watch,
    onMounted,
    onBeforeUnmount,
    onUnmounted,
    defineProps,
    defineEmits,
  } from "vue";
  
  const props = defineProps({
    value: { type: String, required: true },
    config: { type: Object, default: () => ({}) },
    plugins: { type: Array, default: () => [] },
  });
  
  const emit = defineEmits(["updateEvent"]);
  const isActive = ref(false);
  const editor = ref();
  const $el = ref(null);
  
  const loadJodit = async function () {
    const { Jodit } = await import("jodit");
    // @ts-ignore
    editor.value = new Jodit($el.value, props.config);
    editor.value.value = props.value;
    editor.value.events.on("change", (newValue: string) => {
      emit("updateEvent", newValue);
    });
    isActive.value = true;
  };
  
  onMounted(() => {
    import ('jodit/es2021/jodit.min.css');
    loadJodit();
  });
  
  onBeforeUnmount(() => {
    isActive.value = false;
  });
  
  onUnmounted(() => {
    editor.value.destruct();
  });
  </script>
  
  <style scoped>
  .editor {
    opacity: 0;
    transition: opacity 0.1s ease-in-out;
  }
  
  .active {
    opacity: 1;
  }
  </style>
  