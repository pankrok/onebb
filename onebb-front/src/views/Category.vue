<template>
    <CategoryComponent :boxes="1" :loading="loading" :categories="cat"/>
</template>



<script>
import CategoryComponent from '../components/CategoryComponent';
export default {
  name: 'CategoryPage',
  data() {
    return { 
        cat: [],
        loading: true,
    }
  },
  mounted() {
    this.$store.dispatch('onebb/get', {resource:'category', id: this.$route.params.id}).then(response => {
        this.cat.push(response);
        document.title = this.$store.state.defaultTitle + ' - ' + response.name;
        if (response.meta_desc !== null) {
            document.querySelector('meta[name="description"]').setAttribute("content", response.meta_desc);
        } else {
            document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta);
        }
        
        this.loading = false;
        this.$store.dispatch('plugins/reloadPlugins');
    })
  },
  components: {
    CategoryComponent,
  }
}
</script>
