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
  name: 'GroupLisst ',
  data() {
    return {
        resources: null,
        crud: {
            name: 'Groups',
            editRoute: 'GroupEdit',
            listClass: 'list',
            itemListClass: 'list-item mx-2',
            type: '@type',
            buttons: [
                {
                    name: 'add group',
                    class: 'btn-secondary',
                    action: 'create:groupFields',
                    resource: 'group',
                },
            ],
            values: {
                id: 'id',
                name: 'group_name',
            },
            forms: {
                groupFields: [
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'htmlCode',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Html code - you can use variable: {{username}} ',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'groupName',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Group name',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'groupLevel',
                            type: 'number',
                            val: 0,
                            class: 'form-control m-1',
                            label: 'Group level',
                    },
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 row j-c-end my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Create group',
     
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
        this.$store.dispatch('onebb/get', { resource: 'group' }).then(response => {
            this.resources = response['hydra:member'];           
            this.$store.dispatch('loaded');
        });   
    },  
    formEvents: function(formData) {
        if (formData.action === 'groupFields') {
            this.$store.dispatch('loading');
             this.$store.dispatch('onebb/post', {
                resource: 'group',
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
            data: formData.fields
        }).then(() => {
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

