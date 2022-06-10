<template>
<div class="row" :key="$route.name">
  <div class="col-12 mx-1">
      <div class="box">
        <div class="box-header row j-c-space-between a-i-center">
            <h1>{{ $t('skin list') }}</h1>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="box col-4" :class="{ 'box-success': skin.active }" v-for="(skin, id) in resources" :key="id">
                    <div class="box-header">
                        <h3>
                            <span>{{ skin.name }}</span> 
                        </h3>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <span class="badge-wrapper" v-if="skin.active">
                                <span class="badge small">{{ $t('active') }}</span>
                            </span>
                            <div class="col-12 my-2  d-flex  a-i-center j-c-center skin-img">
                                <img :src="skinImg(skin.name)">
                            </div>
                            <div class="col-sm-6 my-1 d-flex a-i-center j-c-center">
                                {{ $t('version') }}: {{ skin.version }}<br />
                            </div>
                            <div class="col-sm-6 my-1  d-flex a-i-center j-c-center">
                                {{ $t('author') }}: {{ skin.author }}
                            </div>
                            
                            <div class="col-sm-4 my-1  d-flex a-i-center j-c-center">
                                <button class="btn btn-secondary m-1" @click="active(id)" :disabled="skin.active"><i class="far fa-check-circle"></i></button>
                            </div>
                            <div class="col-sm-4 my-1 d-flex a-i-center j-c-center">
                                <router-link 
                                            :to="{ name: 'SkinEdit', params: {id: id} }" 
                                            class="btn btn-info"
                                         >
                                         <i class="fas fa-columns"></i>
                                        </router-link>  
                                
                            </div>
                            <div class="col-sm-4 my-1 d-flex a-i-center j-c-center">
                                <button class="btn btn-danger m-1" @click="active(id)"><i class="fas fa-trash"></i></button>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
</div>
</template>
<script>

export default {  
  name: 'SkinList',
  data() {
    return {
        resources: null,
    }
  },
methods: {
    active: function(id){
        alert(id);  
    },
    skinImg(name) {
        return document.getElementById('app').dataset.url + '/skins/' + name + '/' + name +'.png';
    }
},  
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'adminSkin' }).then(response => {
        this.resources = response;           
        this.$store.dispatch('loaded');
    });   
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>
<style scoped>

.small {
  border-radius: 5px !important;
  width: auto !important;
  background-color: var(--green);
}

</style>

