<template>
<div class="row" :key="$route.name">
  <div class="col-12 mx-1">
      <div class="box">
        <div class="box-header row j-c-space-between a-i-center">
            <h1>Email templates list</h1>
        </div>
        <div class="box-content list-item m-0" v-for="temp in templates" :key="temp.template">
            <div class="list-item col-12 d-flex mx-0">
                <router-link :to="{ name: 'EmailEditor', params: {template: temp.template} }"><i class="far fa-file-alt "></i> {{ temp.name }}</router-link>
            </div>          
        </div>
      </div>
  </div>
</div>
</template>
<script>
import templatesJson from '../assets/emailTemplates.json';  

export default {  
  name: 'EmailList',
  data() {
    return {
        templates: templatesJson,
    }
  },       
  mounted() {
    this.$store.dispatch('loaded');
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

