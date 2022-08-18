<template>
  <div class="row" :key="$route.name">
   <div v-for="plugin in $store.state.obbPlugins" class="col-3 my-1" :key="plugin.id">
    <div class="box " :class="{ success: plugin.active}">
        <div class="box-header">
            <h2>{{ plugin.name }}</h2>
        </div>
        <div class="box-content">
        <p>{{ plugin.meta}}</p>
        <hr v-if="plugin.install && plugin.acp" />
        <div v-if="plugin.install && plugin.acp" class="row j-c-center a-i-center">
            <router-link :to="{ name: 'PluginControl',  params: {plugin: plugin.name}}" class="btn btn-secondary"><i class="fa-solid fa-cog"></i> {{ $t('configuration') }}</router-link>
        </div>
        <hr />
        <div class="row j-c-space-between a-i-center">
            <button @click="dispatch(plugin.id, 'install')" v-if="!plugin.install"  class="btn btn-info">Install</button> 
            <button @click="dispatch(plugin.id, 'active')" v-if="plugin.install && !plugin.active" class="btn btn-secondary">Active</button> 
            <button @click="dispatch(plugin.id, 'uninstall')" v-if="plugin.install && !plugin.active" class="btn btn-danger">Uninstall</button> 
            <button @click="dispatch(plugin.id, 'deactive')" v-if="plugin.install && plugin.active" class="btn btn-warning">Deactive</button> 
        </div>
        </div>
    </div>
  </div>
  </div>
</template>

<script>

export default {
  name: 'Plugins',
  data() {
    return {
        plugins: null
    }
  },
  methods: {
    dispatch(plugin, event) {
        this.$store.dispatch('loading');        
        let body = {};
        
        if (event === 'install') {
            body.install = true;
        }
        
        if (event === 'uninstall') {
            body.install = false;
        }
        
        if (event === 'active') {
            body.active = true;
        }
        
        if (event === 'deactive') {
            body.active = false;
        }
        
        this.$store.dispatch('onebb/put', {resource:'plugins', id: plugin, data: body}).then((response) => {
            this.$store.dispatch('obbPlugins/set', {plugin: response.name, data: response});
            this.$store.dispatch('loaded');
        });
    },
    
    load: function() {
        this.$store.dispatch('onebb/get', {resource:'plugins'}).then(response => {
            response['hydra:member'].forEach((el) => {
                this.$store.dispatch('obbPlugins/set', {plugin: el.name, data: el});
            }); 
            this.$store.dispatch('loaded');
        });
    }
  },
  mounted() {
    this.load()
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>
