<script setup lang="ts">
interface InputProps {
  id: string
  modelValue: string | number
  label?: string
  error?: string | null
  labelClassList?: string[]
  type?: string
  classList?: string[]
  wrapperClassList?: string[]
}

defineProps<InputProps>()
const emit = defineEmits(['update:modelValue'])
</script>
<template>
  <div :class="wrapperClassList ?? ['column']">
    <label v-if="label" :for="id" class="padding-s" :class="labelClassList">{{ label }}</label>
    <input
      :id="id"
      :type="type ?? 'text'"
      class="form-control"
      :class="classList ?? ['color-white']"
      :value="modelValue"
      @input="(e: Event) => {
        emit('update:modelValue', (e.target as HTMLInputElement).value)
      }"
    />
    <Transition name="fade" mode="in-out">
      <span
        v-if="error"
        class="border-1 border-radius-5 border-color-red box-shadow-red margin-s padding-m background-red font-weight-600"
        >{{ error }}</span
      >
    </Transition>
  </div>
</template>
