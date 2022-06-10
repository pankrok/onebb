<template>
  <div class="row" :key="$route.name">
  <div class="col-12 my-3 pt-1 mx-1">
     <router-link 
        :to="{ name: 'EmailList' }" 
        class="btn btn-success"
        >
            <i class="fa-solid fa-angle-left"></i> Back
    </router-link>
  </div>
 
      <Transition name="fade">
          <Update
            v-if="!$store.state.loading"
            :crud="crud" 
            :resources="resources"
            :fields="fields"
            @update:form-event="formEvents"
          />
      </Transition>
  </div>
</template>
<script>
import Update from '../components/crud/Update';  

export default {  
  name: 'EmailEditor',
  data() {
    return {
        resources: null,
        crud: {
            name: 'Email template editor',
            class: 'box-header',
            type: '@type',
            forms: {},
            nested: null,
        }
    }
  },  
  methods: {
    reloadCrud: function() {
        this.$store.dispatch('loading');
        this.$store.dispatch('onebb/get', {  
            resource: 'emailEditor',
            subresource: this.$route.params.template,
        }).then(response => {
            this.resources = response;
            this.pageFields();
            this.$store.dispatch('loaded');
        });   
    },
    pageFields() {
        this.crud.form = null;
        this.$store.dispatch('loading');
        this.crud.form = [
                    {
                        fieldType: 'joditType',
                        fieldClass: 'col-12 column my-1',
                        name: 'template',
                        val: this.resources.template ?? '',
                        type: 'textarea',
                        class: 'form-control my-1',
                        label: this.$route.params.template,
                        labelClass: 'my-1 fs24',
                    }, 
                   
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 row j-c-end my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Save',
     
                    }
                ]
                
        this.$store.dispatch('loaded');
    },    
    formEvents: function(formData) {
        this.$store.dispatch('loading');
        this.$store.dispatch('onebb/put', {  
            resource: 'emailEditor',
            id: this.$route.params.template,
            data: formData.fields
        }).then(() => {
            this.$store.dispatch('loaded');
        });
        
    }
  },
  components: {
    Update
  },
  mounted() {
  console.log(this.$route.params.template);
    this.reloadCrud()     
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

