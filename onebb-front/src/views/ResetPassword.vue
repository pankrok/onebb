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
                            <strong>{{ $t('reset email password was send to your email') }}</strong>
                        </div>
                    </div>
                </div>
                <div v-else class="box-content row">
                    <div class="col-12 column mx-4">
                      <label for="email">{{ $t('email') }}</label>
                      <input v-model="email" type="email" name="email" id="email" class="form-control">
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
  name: 'ResetPassword',
  data() {
    return {
        loading: true,
        email: null,
        success: false,
    }
  },
  methods: { 
    changePassword() {
        this.loading = true;
        this.$store.dispatch('onebb/post', { 
            resource: 'forgetPassword', 
            data: {
                email: this.email
            }    
        }).then(response => {
            this.success = response[0];
            this.loading = false;
        });    
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
    