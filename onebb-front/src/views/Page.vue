<template>
<Transition name="fade" mode="out-in">
<div class="box" v-if="page.active" :key="page.id">
    <div class="box-header">
        <h1>{{ page.name }}</h1>
    </div>
        <div class="box-content p-2" v-html="page.content">
    </div> 
</div>
</Transition>
</template>



<script>

export default {
  name: 'Page',
  data() {
    return { 
        page: [],
    }
  },
  methods: {
    init() {
        this.$store.dispatch('onebb/get', {resource:'page', id: this.$route.params.id}).then(response => {
            this.page = response;
            document.title = this.$store.state.defaultTitle + ' - ' + response.name;
            if (response.meta_desc !== null) {
                document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta + '-' + response.meta_desc);
            } else {
                document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta + '-' + response.name);
            }
        });
    }
  },
  
  mounted() {
    this.init();
  }
}
</script>