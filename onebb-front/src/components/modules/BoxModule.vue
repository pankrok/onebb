<template>
  <div class="list-complete-item box">
          <div class="box-header">
            <h4>{{ name }}</h4>
          </div>
      <div class="box-content"> {{ content }}
        <button class="btn btn-secondary" @click="changeLang">{{ $t('language') }}</button><br /><br />
      </div> 
    </div>
</template>

<script>
export default {
  name: 'BoxModule',
  props: {
    name: String,
    content: String,
  },
  methods: {
    changeLang() {
    
        console.log('change lang : ' + localStorage.getItem('locale'));
    
        if ( localStorage.getItem('locale') == 'en' ) {
            this.$i18n.locale = 'pl';
            localStorage.setItem('locale', 'pl')
            return;
        }
        
        if ( localStorage.getItem('locale') == 'pl' ) {
            this.$i18n.locale = 'en';
            localStorage.setItem('locale', 'en')
            return;
        }
    },
    
  }
}
</script>

