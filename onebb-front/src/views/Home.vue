<template>

    <CategoryComponent :boxes="2" :loading="loading" :categories="home" />

</template>



<script>
import CategoryComponent from '../components/CategoryComponent';
export default {
  name: 'Home',
  data() {
    return {
        home: null,
        loading: true,
    }
  },
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'category' }).then(response => {
        this.home = response['hydra:member'];
        this.loading = false;
        this.$store.dispatch('plugins/reloadPlugins');
    });    
  },
  components: {
    CategoryComponent,
  }
}
</script>
