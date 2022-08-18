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
import roles from '../assets/roles.json'

export default {  
  name: 'User',
  data() {
    return {
        resources: null,
        group: null,
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
                userFields: []
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
    formEvents: function(formData) { 
        if (formData.action === 'userFields') {
            this.$store.dispatch('loading');
             this.$store.dispatch('onebb/post', {
                resource: 'user',
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
        }).then(() => {
            this.$store.dispatch('loaded');
        });
    }
  },
  components: {
    Crud
  },
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'group' }).then(response => {
        let options = [];
        let userRoles = [];
        response['hydra:member'].forEach(function(el) {
            options.push({
                val:  el.id,
                name: el.group_name
            });
        });
        roles.forEach((role) => {
            let r = {
                name: role, // FIXME here should be used translations!
                val: role
            };
             
            userRoles.push(r);
        });
        this.group = options;
        this.crud.forms.userFields = [
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-6 column my-1',
                            name: 'username',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Username',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-6 column my-1',
                            name: 'email',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Email',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-6 column my-1',
                            name: 'password',
                            type: 'password',
                            class: 'form-control m-1',
                            label: 'Password',
                    },
                    {
                        fieldType: 'selectType',
                        fieldClass: 'col-6  column my-1',
                        name: 'default_group',
                        class: 'form-control my-1',
                        label: 'Select user group',
                        options: this.group
                    },
                    {
                        fieldType: 'selectType',
                        fieldClass: 'col-12 row d-flex j-c-center a-i-center py-1 border-bottom',
                        name: 'roles',
                        multiple: true,
                        styles: "width: 100%; height: 250px;",
                        options: userRoles,
                        class: 'form-control m-1',
                        label: 'Roles',
        
                    },
                    {
                        fieldType: 'checkboxType',
                        fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1 border-bottom',
                        checked: true,
                        name: 'verified',
                        class: 'form-control m-1',
                        label: 'Verified',
      
                    },
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1 border-bottom',
                            checked: false,
                            name: 'banned',
                            class: 'form-control m-1',
                            label: 'Banned',
      
                    },
                    {
                        fieldType: 'checkboxType',
                        fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1 border-bottom',
                        checked: false,
                        name: 'acpEnable',
                        val: false,
                        class: 'form-control m-1',
                        label: 'ACP',
                    },
                    {
                        fieldType: 'checkboxType',
                        fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1 border-bottom',
                        checked: false,
                        name: 'mcpEnable',
                        val: false,
                        class: 'form-control m-1',
                        label: 'MCP',
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
        this.reloadCrud();  
    });    
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

