<template>
    <div class="f-grow" :key="$route.name">
        <BoardComponent :boxes="1" :loading="loading" :board="board"/>
    </div>
</template>



<script>
import BoardComponent from '../components/BoardComponent';

export default {
  name: 'Plot',
  data() {
    return {
        board: null,
        loading: true,
    }
  },
  mounted() {
    this.$store.dispatch('onebb/get', {resource:'board', id: this.$route.params.id}).then(response => {
        this.board = response;
        document.title = this.$store.state.defaultTitle + ' - ' + response.name;
        if (response.meta_desc !== null) {
            document.querySelector('meta[name="description"]').setAttribute("content", response.meta_desc);
        } else {
            document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta);
        }
   
        this.loading = false;
    })
  },
  components: {
    BoardComponent
  }
}
</script>
