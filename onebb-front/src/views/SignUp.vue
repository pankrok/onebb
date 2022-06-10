<template>
 <div class="f-grow" :key="$route.name">
    <div class="box">
      <div class="box-header">
        <h2><a href="#">{{ $t('sign up') }}</a></h2>
      </div>
      <div class="box-content">
        <div class="row">
            <div v-if="msg !== ''" class="col-12 column alert" :class="[error ? 'danger' : 'success']">
             <div class="alert-body"> {{ $t(msg) }} </div>
            </div>
            <div class="col-12 column">
                <label for="login" >login</label>
                <input v-model="user.username" type="text" name="login" id="login" class="form-control mx-1">
            </div>
            <div class="col-6 column">
                <label for="password">password</label>
                <input v-model="user.password" type="password" name="password" id="password" class="form-control mx-1">
            </div>
            <div class="col-6 column">
                <label for="vpassword" >verifie password</label>
                <input v-model="validate.password" type="password" name="vpassword" id="vpassword" class="form-control mx-1">
            </div>
            <div class="col-6 column">
                <label for="email">email</label>
                <input v-model="user.email" type="email" name="email" id="email" class="form-control mx-1">
            </div>
            <div class="col-6 column">
                <label for="vemail" >verfie email</label>
                <input v-model="validate.email" type="email" name="vemail" id="vemail" class="form-control mx-1">
            </div>
            <div class="col-12 column my-1 mx-1">
                <button @click="registerBtn" class="btn btn-secondary">{{ $t('register') }}</button>
            </div>
        </div>
      </div>
  </div>
    </div>
</template>
<script>
export default {
  name: 'SignUp',
  data() {
    return {
        error: false,
        msg: '',
        user: { 
            username: null,
            password: null,
            email: null,
        },
        validate: {
            password: null,
            email: null
        }
    }
  },
  methods: {
    registerBtn() {
        this.msg = '';
        if ((this.user.password !== this.validate.password) || this.user.password === null || this.validate.password === null ) {
            this.error = true;
            this.msg = 'passwords not match';
            return;
        }
        
        if (this.user.email !== this.validate.email || this.user.email === null || this.validate.email === null ) {
            this.error = true;
            this.msg = 'emails not match';
            return;
        }
        
        this.$store.dispatch('onebb/post', {resource: 'user', data: this.user}).then(ret => {
           console.log(ret.violations);
           if (ret.violations) {
                ret.violations.forEach((val) => {
                    this.msg += ' ' + val.message;
                }); 
                
                this.error = true;
           } else {
                this.error = false;
                this.msg = 'Congratulations, your account has been successfully created';
           }
        });
        
        
    }
  },
  mounted() {
    this.$store.dispatch('plugins/reloadPlugins');
  },
  components: {
  }
}
</script>