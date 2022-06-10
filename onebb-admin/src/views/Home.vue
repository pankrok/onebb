<template>
  <div class="row" :key="$route.name">
   <div class="col-12 my-1">
        <div class="box success">
            <div class="box-header">
                <h2>OneBB</h2>
            </div>
            <div class="box-content">
            <p>Hello admin, lets start work!</p>
            </div>
        </div>
    </div>
   <!-- <div class="col-3">
        <div class="box danger">
            <div class="box-header">
                <h2>BOX</h2>
            </div>
            <div class="box-content">
            <p>Lorem Ipsum</p>
            </div>
        </div>
    </div>
    
     <div class="col-3">
        <div class="box success">
            <div class="box-header">
                <h2>BOX</h2>
            </div>
            <div class="box-content">
            <p>Lorem Ipsum</p>
            </div>
        </div>
    </div>
    
     <div class="col-3">
        <div class="box warning">
            <div class="box-header">
                <h2>BOX</h2>
            </div>
            <div class="box-content">
            <p>Lorem Ipsum</p>
            </div>
        </div>
    </div>
    
     <div class="col-3">
        <div class="box info">
            <div class="box-header">
                <h2>BOX</h2>
            </div>
            <div class="box-content">
            <p>Lorem Ipsum</p>
            </div>
        </div>
    </div> -->
    
    
  </div>
</template>

<script>

export default {
  name: 'Home',
  components: {

  },
  mounted() {
    this.$store.dispatch('loaded');
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>
