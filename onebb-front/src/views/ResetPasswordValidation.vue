<template>
<Transition name="fade" mode="out-in">
    <div v-if="!loading" class="f-grow row" :key="$route.name"> 
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                <h1> {{ $t('reset password') }}</h1>
                </div>
                <div v-if="success" class="box-content row">
                    <div class="col-12 column mx-4">
                        <div class="alert success text-center">
                            <strong>{{ $t('your password has been changed') }}</strong>
                        </div>
                    </div>
                </div>
                <div v-else class="box-content row">
                    <div class="col-12 column mx-4">
                      <label for="password">{{ $t('password') }}</label>
                      <input v-model="password" type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="col-12 column mx-4">
                      <label for="vpassword">{{ $t('repeat password') }}</label>
                      <input v-model="vpassword" type="password" name="vpassword" id="vpassword" class="form-control">
                    </div>
                    <div class="col column my-1">
                      <button  @click="changePassword" type="submit" class="btn btn-secondary">{{ $t('reset password') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="f-grow row" :key="skeleton">
        <div class="col-12">
            <Skeleton :boxes="1" /> 
        </div>
    </div>
</Transition>
</template>



<script>
import Skeleton from '../components/skeletons/SkeletonPlot';

export default {
  name: 'ResetPasswordValidation',
  data() {
    return {
        loading: true,
        password: null,
        vpassword: null,
        success: false, 
    }
  },
  methods: { 
    changePassword() {
        if (this.password === this.vpassword) {
            this.loading = true;
            this.$store.dispatch('onebb/post', { 
                resource: 'resetPassword', 
                data: {
                    hash: this.$route.params.hash,
                    password: this.password
                }    
            }).then(response => {
                this.success = response[0];
                this.loading = false;
            });  
        }
    }
    
  },
  mounted() {
    this.loading = false;
    this.$store.dispatch('plugins/reloadPlugins');    
  },
  components: {
    Skeleton
  }
}
</script>
    