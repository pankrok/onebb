<template>
    <div class="f-grow" :key="$route.name">
        <div v-if="$store.state.onebb.status.loggedIn"  class="box">
        <div class="col-12 column">
                <label for="topic" data-v-30ce6f0d="">{{ $t('topic') }}</label>
                <input v-model="name" type="text" name="topic" id="topic" class="form-control mx-1" :disabled="loading">
            </div>
        <Transition name="slide-fade" mode="out-in"> 
             <div v-if="$store.state.onebb.status.loggedIn" class="box-content column" :key="jodit">
                <Jodit v-model:value="content" :config="jodit_cfg" />
                <button class="btn btn-secondary my-1 a-s-end" @click="sendPost">Prześlij odpowiedź</button>
            </div>
        </Transition>
        </div>  
    </div>
</template>



<script>

import { defineAsyncComponent } from 'vue'

export default {
  name: 'Plot',
  data() {
    return {
        content: '',
        name: '',
        loading: false,
        jodit_cfg: {},
    }
  },
  mounted() {

  },
  methods: {
    sendPost: function() {
        this.loading = true;
        this.$store.dispatch('onebb/post', {
            resource: 'plot',
            data: {
                name: this.name,
                board: "/api/boards/" + this.$route.params.id,
            }
        }).then(response => { 
            console.log({newplot: response});
            let id = response.id;
            let slug = response.slug;
            this.$store.dispatch('onebb/post', {
                resource: 'replay',
                data: {
                    plot: "/api/plots/" + id,
                    content: this.content
                }
                }).then(() => {
                    this.$router.push({ name: 'Plot', params: { id: id, slug: slug } })
                });
            
        });
    },
  },
  components: {
    Jodit: defineAsyncComponent(() =>
        import('../components/modules/JoditEditor')
    ),
  }
}
</script>

<styles scoped>

.jodit-toolbar__box:not(:empty) {
  background-color: var(--primary) !important;
  border-bottom: 1px solid var(--background) !important;
}

.jodit-ui-separator {
  border-left: 0;
  border-right: 1px solid var(--background) !important;
}


</styles>
