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
    document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta + '-' + this.$t('email validation'));
    document.title = this.$store.state.defaultTitle + ' - ' + this.$t('email validation');
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
