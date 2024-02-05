<template>
  <div class="row" :key="$route.name">
   <div v-for="plugin in plugins" class="col-3 my-1" :key="plugin.id">
    <div class="box " :class="{ success: plugin.active}">
        <div class="box-header">
            <h2 class="text-center">{{ plugin.name }}</h2>
        </div>
        <div class="box-content">
        <div v-if="plugin.main_pic" class="plugin-img my-1">
            <img class="plugin-img-rwd" :src="image(plugin.main_pic)">
        </div>
        <div class="plugin-desc" v-html="plugin.short_desc"></div>
        <p>version: {{ plugin.version[0].version }}</p>
        <p>last update: {{plugin.updated_at}}</p>
        <hr />
        <div class="row j-c-space-between a-i-center">
            <button  class="btn btn-success">More info</button> 
            <button @click='download(plugin.version[0].file_dir)' class="btn btn-info">Download</button> 
        </div>
        </div>
    </div>
  </div>
  </div>
</template>

<script>

export default {
  name: 'PluginStore',
  data() {
    return {
        plugins: null,
        storeUrl:  process.env.VUE_APP_OBB_PANEL_URL
    }
  },
  methods: {
    download(url) {
        this.$store.dispatch('onebb/post', {
            resource: 'pluginDownload', 
            data: {
                path: this.storeUrl + url
            } 
        }).then(response => {
            console.log(response, this.$store.state.obbPlugins);
        });
    },
    image(img) {
        return this.storeUrl + '/uploads/plugins/img/' + img;
    }
  },
  mounted() {
    try {
    fetch(this.storeUrl + '/api/plugins')
      .then((response) => response.json())
      .then((data) => { 
        this.$store.dispatch('loaded');
        this.plugins = data['hydra:member']; 
      });
    } catch(e) {
      console.error('/api/plugins', {e})
      this.$store.dispatch('loaded');
    }
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

<style>

.plugin-img {
    display:flex;
    justify-content: center;
    align-items: center;
}


.plugin-img-rwd,
figure > a > img {
    max-width: 100% !important;
    height: auto !important;
    max-height: 100% !important;
}

</style>
