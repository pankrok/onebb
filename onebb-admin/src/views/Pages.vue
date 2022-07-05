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
  name: 'Pages',
  data() {
    return {
        resources: null,
        crud: {
            name: 'Page',
            editRoute: 'PageEdit',
            listClass: 'list',
            itemListClass: 'list-item mx-2 px-2',
            type: '@type',
            buttons: [
                {
                    name: 'add page',
                    class: 'btn-secondary',
                    action: 'create:pageFields',
                    resource: 'page',
                },
            ],
            nested: {},
            values: {
                id: 'id',
                name: 'name',
                checkbox: 'active'
            },
            forms: {
                pageFields: [
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'name',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Name',
                    },
                    {
                            fieldType: 'joditType',
                            fieldClass: 'col-12 column my-1',
                            name: 'content',
                            type: 'jodit',
                            class: 'form-control m-1',
                            label: 'Content',
                    },
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-12 row d-flex j-c-center a-i-center py-1 border-bottom',
                            checked: true,
                            name: 'active',
                            class: 'form-control m-1',
                            label: 'Active',
      
                    },
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 row j-c-end my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Create page',
     
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
        this.$store.dispatch('onebb/get', { resource: 'page' }).then(response => {
            this.resources = response['hydra:member'];
            this.$store.dispatch('loaded');
        });   
    },  
    formEvents: function(formData) {
    
        if (formData.action === 'pageFields') {
            this.$store.dispatch('loading');
             this.$store.dispatch('onebb/post', {
                resource: 'page',
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

