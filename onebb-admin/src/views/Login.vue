<template>
<div class="row h100vh j-c-center a-i-center" :key="$route.name">

     <Transition name="slide-fade" mode="out-in">
    <div v-if="error == true || success == true" class="alert column" :class="{ success: success, danger: error }">
        <div class="alert-body">
            <strong v-if="success">you are logged in</strong>
            <strong v-if="error"> invalid email or password</strong>
         </div>
    </div>
  <div v-else  class="box" style="width: 300px;">
    <div class="box-header text-center">
      <h4>sign in</h4>
    </div>
   
    <div class="box-content">
           <span v-if="$store.state.loading" class="box-loader"></span>
        <div class="col-12 column">
          <label for="exampleInputEmail1">login</label>
          <input v-model="username" type="text" name="login" id="login" class="form-control" :class="{ 'form-control-disabled': logging }" :disabled="logging">

        </div>
        <div class="col-12 column">
          <label for="exampleInputPassword1">password</label>
          <input v-model="password" type="password" name="password" id="password" class="form-control" :class="{ 'form-control-disabled': logging }" :disabled="logging">
        </div>
        <div class="col column my-1">
          <button :disabled="logging" :class="{ 'btn-disabled': logging }" @click="login" type="submit" class="btn btn-secondary">sign in</button>
        </div>
 
    </div>
    
  </div>
  </Transition>

</div>

</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
        username: null,
        password: null,
        logging: false,
        error: false,
        success: false,
    }
  },
  mounted() {
    this.$store.dispatch('loaded');
  },
  methods: {
    login() {
        this.$store.dispatch('loading');
        this.logging = true;
        this.$store.dispatch('onebb/login', {
            username: this.username,
            password: this.password
        }).then(ret => {
            if (ret.success == true) {
                this.logging = false;
                this.success = true;
                setTimeout(() => {
                    this.success = false;
                    this.$router.push({ name: 'Home' });
                }, 1000);
            } else {
                this.$store.dispatch('loaded');
                this.logging = false;
                this.error = true;
                setTimeout(() => {
                   this.error = false;
                }, 2000);
            } 
        });
    }
  }
}
</script>
