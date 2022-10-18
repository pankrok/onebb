<template>
<Transition name="fade">
<div v-if="show" @click.self="$emit('update:show', false)" class="modal">
     <Transition name="slide-fade" mode="out-in">
    <div v-if="error == true || success == true" class="alert column" :class="{ success: success, danger: error }">
        <div class="alert-body">
            <strong v-if="success"> {{ $t('you are logged in') }} </strong>
            <strong v-if="error"> {{ $t('invalid email or password') }} </strong>
         </div>
    </div>
  <div v-else  class="box" style="width: 300px;">
    <div class="box-header text-center">
      <h4>{{ $t('sign in') }}</h4>
    </div>
   
    <div class="box-content">
        <span v-if="logging" class="box-loader"></span>
        <div class="col-12 column">
          <label for="exampleInputEmail1">{{ $t('Login') }}</label>
          <input v-model="username" type="text" name="login" id="login" class="form-control" :class="{ 'form-control-disabled': logging }" :disabled="logging">

        </div>
        <div class="col-12 column">
          <label for="exampleInputPassword1">{{ $t('Password') }}</label>
          <input v-model="password" type="password" name="password" id="password" class="form-control" :class="{ 'form-control-disabled': logging }" :disabled="logging">
        </div>
        <div class="col column my-1">
          <button :disabled="logging" :class="{ 'btn-disabled': logging }" @click="login" type="submit" class="btn btn-secondary">{{ $t('sign in') }}</button>
        </div>
        <hr />
        <div class="col column my-1 text-center">
        
          <router-link  @click="$emit('update:show', false)"
            :to="{ name: 'ResetPassword' }"
           >
                <small>{{ $t('Forget password') }}</small>
           </router-link>
        </div>
    </div>
    
  </div>
  </Transition>
</div>
</Transition>
</template>

<script>
export default {
  name: 'SignInModule',
  props: {
    show: Boolean,
  },
  data() {
    return {
        username: null,
        password: null,
        logging: false,
        error: false,
        success: false,
    }
  },
  methods: {  
    login() {
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
                    this.$emit('update:show', false)
                }, 1000);
            } else {
                this.logging = false;
                this.error = true;
                setTimeout(() => {
                   this.error = false;
                }, 2000);
            } 
        });
    }
  },
  emits: ['update:show']
}
</script>
<style scoped>
   @import '../../../assets/modal.css';
</style>
