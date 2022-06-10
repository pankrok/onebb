<template>
  <div class="row" :key="$route.name">
  <div class="col-12 mx-1">
    
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
  name: 'Configuration',
  data() {
    return {
        resources: null,
        crud: {
            name: 'Onebb Configuration',
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
            resource: 'configuration'
        }).then(response => {
            this.resources = response.parameters;
            this.pageFields();
            this.$store.dispatch('loaded');
        });   
    },
    pageFields() {
        this.crud.form = null;
        this.$store.dispatch('loading');
        this.crud.form = [
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'version',
                            val: this.resources.version,
                            disabled: true,
                            type: 'text',
                            class: 'form-control m-1 form-control-disabled',
                            label: 'OneBB Version',
                    }, 
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-6 column my-1',
                            name: 'base_url',
                            val: this.resources.base_url,
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Base OneBB url',
                    }, 
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-6 column my-1',
                            name: 'acp_url',
                            val: this.resources.acp_url,
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Admin control panel url',
                    }, 
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-3 column my-1',
                            name: 'mailer.username',
                            val: this.resources['mailer.dns']['username'],
                            type: 'email',
                            class: 'form-control m-1',
                            label: 'SMTP username',
                    }, 
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-3 column my-1',
                            name: 'mailer.password',
                            val: this.resources['mailer.dns']['password'],
                            type: 'password',
                            class: 'form-control m-1',
                            label: 'SMTP password',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-3 column my-1',
                            name: 'mailer.server',
                            val: this.resources['mailer.dns']['server'],
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'SMTP server',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-3 column my-1',
                            name: 'mailer.port',
                            val: this.resources['mailer.dns']['port'],
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'SMTP port',
                    },
                    
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-4 column my-1',
                            name: 'mail.address',
                            val: this.resources['mail.address'],
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Mail address',
                    }, 
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-4 column my-1',
                            name: 'mail.username',
                            val: this.resources['mail.username'],
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Mail username',
                    }, 
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-4 row d-flex j-c-center a-i-center py-1',
                            checked: this.resources['mail.validation'],
                            name: 'mail.validation',
                            class: 'form-control m-1',
                            label: 'Email validation',
      
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
        this.$store.dispatch('onebb/post', {  
            resource: 'configuration',
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
    this.reloadCrud()     
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

