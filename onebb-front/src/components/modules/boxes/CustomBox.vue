<template>
  <div class="list-complete-item box">
    <div class="box-header">
      <h3 class="text-center">{{ name }}</h3>
    </div>
    <div 
      class="box-content"
      v-html="content">
    </div>
  </div> 
</template>

<script>
export default {
  name: 'CustomBox',
  props: {
    name: String,
    content: String,
  }
}
</script>

