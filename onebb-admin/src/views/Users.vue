<template>
  <div class="row" :key="$route.name">
      <!-- crud vue -->
      <Crud 
        :crud="crud" 
        :resources="resources"
        @update:crud-form="formEvents"
        @update:crud-checkbox="checkboxEvent"
      />
  </div>
</template>
<script>
import Crud from '../components/Crud';  

export default {  
  name: 'User',
  data() {
    return {
        resources: null,
        crud: {
            name: 'User',
            editRoute: 'UserEdit',
            listClass: 'list',
            itemListClass: 'list-item mx-2 px-2',
            type: '@type',
            buttons: [
                {
                    name: 'add user',
                    class: 'btn-secondary',
                    action: 'create:userFields',
                    resource: 'userAdmin',
                },
            ],
            nested: {},
            values: {
                id: 'id',
                name: 'username',
                checkbox: 'banned'
            },
            forms: {
                userFields: [
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'username',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Username',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'password',
                            type: 'password',
                            class: 'form-control m-1',
                            label: 'Password',
                    },
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-12 row d-flex j-c-center a-i-center py-1 border-bottom',
                            checked: true,
                            name: 'verified',
                            class: 'form-control m-1',
                            label: 'Verified',
      
                    },
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-12 row d-flex j-c-center a-i-center py-1 border-bottom',
                            checked: false,
                            name: 'banned',
                            class: 'form-control m-1',
                            label: 'Banned',
      
                    },
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 row j-c-end my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Create user',
     
                    }
                ],
            },
            list: true,
            add: true,
            edit: true,
            delete: true,
            show: true,
            
        }
    }
  },  
  methods: {
    reloadCrud: function() {
        this.$store.dispatch('onebb/get', { resource: 'user' }).then(response => {
            this.resources = response['hydra:member'];
            this.$store.dispatch('loaded');
        });   
    },  
    formEvents: function(formData) { console.log({formData: formData});
        if (formData.action === 'categoryFields') {
            this.$store.dispatch('loading');
             this.$store.dispatch('onebb/post', {
                resource: 'category',
                data: formData.fields
            }).then(() => {
                this.$store.dispatch('loaded');
                this.reloadCrud();
            });
        }
        
        if (formData.action === 'boardFields') {
            this.$store.dispatch('loading');
             this.$store.dispatch('onebb/post', {
                resource: 'board',
                data: formData.fields
            }).then(() => {
                this.$store.dispatch('loaded');
                this.reloadCrud();
            });
        }
        
        if (formData.action === 'itemDelete') {
            this.$store.dispatch('loading');
             this.$store.dispatch('onebb/delete', {
                resource: formData.fields.resource,
                id: formData.fields.id
            }).then(() => {
                this.$store.dispatch('loaded');
                this.reloadCrud();
            });
        }
    },
    checkboxEvent: function(formData) {
        this.$store.dispatch('loading');
        this.$store.dispatch('onebb/put', { 
            id: formData.id,  
            resource: formData.resource,
            data: {banned: formData.fields.active}
        }).then(response => {
            console.log(response);
            this.$store.dispatch('loaded');
        });
    }
  },
  components: {
    Crud
  },
  mounted() {
    this.reloadCrud()     
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

