<template>
<div class="box p-1">
    LOADING: {{ loading }}<br />
    ALERT CONFIRM EMAIL: {{ success }}
</div>
</template>



<script>

export default {
  name: 'EmailValidation',
  data() {
    return {
       loading: true,
       success: false
    }
  },
  mounted() {
    this.$store.dispatch('onebb/post', { 
        resource: 'validation', 
        data: {
            hash: this.$route.params.hash,
        }
    }).then(response => {
        this.success = response.success;
    });    
  }
}
</script>
