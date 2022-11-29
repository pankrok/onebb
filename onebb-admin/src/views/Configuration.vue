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
       <div class="box col-12 my-2">
            <div class="box-content row j-c-center a-i-center">
                <button class="btn btn-warning" @click="claerCache" >{{ $t('clear cache') }}</button>
            </div>
            <Transition name="fade">
            <div v-if="cache" class="modal">
                <div class="alert column console">
                    <div class="alert-body">
                        <h3 class="text-center">Console log:</h3>
                        <span v-html="cache"></span>
                     </div>
                </div>
            </div>
            </Transition>
      </div>
  </div>
</template>
<script>
import Update from '../components/crud/Update';  

export default {  
  name: 'Configuration',
  data() {
    return {
        resources: null,
        options: [],
        cache: false,
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
        this.$store.dispatch('onebb/get', { resource: 'group' }).then(response => {
            let options = [];
            response['hydra:member'].forEach(function(el) {
                options.push({
                    val:  el.id,
                    name: el.group_name
                });
            });
            this.options = options;
            this.$store.dispatch('onebb/get', {  
                resource: 'configuration'
            }).then(response => {
                this.resources = response.parameters;
                this.cfgFields();
                this.$store.dispatch('loaded');
            });  
        });
         
    },
    
    cfgFields() {
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
                            fieldClass: 'col-12 column my-1',
                            name: 'board_name',
                            val: this.resources.board_name,
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Forum name',
                    },
                    {
                        fieldType: 'checkboxType',
                        fieldClass: 'col-3 my-1 row j-c-center a-i-center',
                        checked: this.resources.maintaince,
                        name: 'maintaince',
                        class: 'form-control my-1',
                        label: 'Maintaince',

                    },
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1',
                            checked: this.resources['mail.validation'],
                            name: 'mail.validation',
                            class: 'form-control m-1',
                            label: 'Email validation',
      
                    },
                    {
                        fieldType: 'selectType',
                        fieldClass: 'col-6 column my-1',
                        val: this.resources.default_group,
                        name: 'default_gruop',
                        class: 'form-control my-1',
                        label: 'Select new use default group',
                        options: this.options
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
                            fieldClass: 'col-6 column my-1',
                            name: 'mail.address',
                            val: this.resources['mail.address'],
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Mail address',
                    }, 
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-6 column my-1',
                            name: 'mail.username',
                            val: this.resources['mail.username'],
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Mail username',
                    }, 
                     {
                            fieldType: 'textareaType',
                            fieldClass: 'col-12 column my-1',
                            name: 'meta.description',
                            val: this.resources['meta.description'],
                            type: 'text',
                            class: 'form-control my-1',
                            label: 'Homepage meta description',
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
        
    },
    
    claerCache() {
        this.$store.dispatch('loading');
        this.$store.dispatch('onebb/put', {  
            resource: 'cache',
        }).then(response => {
            this.cache = response;
            setTimeout(()=> {
              this.cache = false;
            }, 5000);
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

<style scoped>

.console {
  margin: 2rem;
  padding: 2rem;
  border: 1px solid rgba(0,0,0,.125);
  border-radius: .25rem;
  background: black;
  color: #4AF626;
}

</style>