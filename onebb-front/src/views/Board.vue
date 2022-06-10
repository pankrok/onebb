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
        this.loading = false;
    })
  },
  components: {
    BoardComponent
  }
}
</script>
