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
  name: 'Boards ',
  data() {
    return {
        resources: null,
        crud: {
            name: 'Boards',
            editRoute: 'ForumEdit',
            listClass: 'box',
            itemListClass: 'box-header',
            type: '@type',
            buttons: [
                {
                    name: 'add category',
                    class: 'btn-secondary',
                    action: 'create:categoryFields',
                    resource: 'category',
                },
                {
                    name: 'add board',
                    class: 'btn-secondary',
                    action: 'create:boardFields',
                    resource: 'board',
                }
            ],
            nested: {
                class: 'box-content',
                id: 'id',
                name: 'name',
                checkbox: 'active',
                edit: true,
                delete: true,
                resource: 'board',
            },
            values: {
                id: 'id',
                name: 'name',
                checkbox: 'active',
                nested: 'boards'
            },
            forms: {
                categoryFields: [
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'name',
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Category name',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'priority',
                            type: 'number',
                            val: 0,
                            class: 'form-control m-1',
                            label: 'Category priority',
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
                            text: 'Create category',
     
                    }
                ],
                boardFields: [
                
                    {
                            fieldType: 'selectType',
                            fieldClass: 'col-12 column my-1',
                            name: 'category',
                            class: 'form-control my-1',
                            label: 'Select board category',
                            options: []
     
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'name',
                            type: 'text',
                            class: 'form-control my-1',
                            label: 'Board name',
                    },
                    {
                            fieldType: 'joditType',
                            fieldClass: 'col-12 column my-1',
                            name: 'Description',
                            type: 'jodit',
                            class: 'form-control my-1',
                            label: 'Description',
                    },
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-12 my-1 row j-c-center a-i-center',
                            checked: true,
                            name: 'active',
                            class: 'form-control my-1',
                            label: 'Active',
      
                    },
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 column my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Create Board',
     
                    }
                ]
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
        this.$store.dispatch('onebb/get', { resource: 'category' }).then(response => {
            this.resources = response['hydra:member'];
            let handler = [];
            response['hydra:member'].forEach(function(el) {
                handler.push({
                    val: '/api/categories/' + el.id,
                    name: el.name
                });
            });

            this.crud.forms.boardFields[0].options = handler;
            
            this.$store.dispatch('loaded');
        });   
    },  
    formEvents: function(formData) {
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
        
        if (formData.action === 'itemDelete' || formData.action === 'itemNestedDelete' ) {
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

