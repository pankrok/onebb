<template>
  <div class="row j-c-center" :key="$route.params.plugin + $route.params.temp + $route.params.script">
    <div class="box">
        <div class="box-header">
            <h2>{{ $route.params.plugin }}</h2>
        </div>
        <div class="box-content" v-html="adminPlugin">
        </div>
    </div>
  </div>
</template>

<script>
import 'jodit/build/jodit.min.css'
import { Jodit } from 'jodit'

export default {
  name: 'PluginControl',
  data() {
    return {
        adminPlugin: null,
        adminScript: null,
        route: this.$route.params
    }
  },
  watch:{
    $route (){
        this.$store.dispatch('loading');
        this.load();
    }
  }, 
  methods: {
    load: function() {
        this.$store.dispatch('onebb/post', {resource:'pluginControl', data: {
            plugin: this.$route.params.plugin,
            temp: this.$route.params.temp ?? null,
            script: this.$route.params.script ?? null
        }}).then(response => { 
            this.adminPlugin = response.template;
            this.adminScript = response.script;
        }).then(() => {
            if (this.adminScript) {
                eval(this.adminScript)
            }
            this.$store.dispatch('loaded');
        });
    }
  },
  mounted() {
    this.load()
    window.$jodit = Jodit;
    let path = this.$router.resolve({ 
          name: 'PluginControl',
          params: { plugin: this.$route.params.plugin },
        });
    window.$path = path.href;    
        
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>
