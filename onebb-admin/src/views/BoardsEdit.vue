<template>
  <div class="row" :key="$route.name">
  <div class="col-12 my-3 pt-1 mx-1">
     <router-link 
        :to="{ name: 'ForumList' }" 
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
      <Modal v-model:show="show" :data="modal" />
  </div>
</template>
<script>
import Modal from '../components/Modal';  
import Update from '../components/crud/Update';  

export default {  
  name: 'BoardsEdit',
  data() {
    return {
        resources: null,
        show: false,
        modal: {
            success: false,
            error: false,
            message: null,
        },
        crud: {
            name: 'Edit',
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
            id: this.$route.params.id,  
            resource: this.$route.params.resource 
        }).then(response => {
            this.resources = response;
            if (this.$route.params.resource === 'category') {
                this.categoryFields();
            }
            if (this.$route.params.resource === 'board') {
                this.boardFields();
               }
            this.$store.dispatch('loaded');
        });   
    },
    categoryFields() {
        this.crud.form = null;
        this.crud.form = [
                     {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-2 row d-flex j-c-center a-i-center py-1',
                            checked: this.resources.active,
                            name: 'active',
                            class: 'form-control m-1',
                            label: 'Active',
      
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-5  row a-i-center j-c-center my-1',
                            name: 'name',
                            type: 'text',
                            val: this.resources.name,
                            class: 'form-control m-1',
                            label: 'Category name',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-5 row a-i-center j-c-center my-1',
                            val: this.resources.priority ?? 0,
                            name: 'priority',
                            type: 'number',
                            class: 'form-control m-1',
                            label: 'Category priority',
                    },
                    {
                         fieldType: 'buttonType',
                        fieldClass: 'col-6 row pt-4 border-top',
                        name: 'submit',
                        type: 'button',
                        class: 'btn btn-secondary',
                        text: 'Save and exit',
                    },
                    {
                        fieldType: 'buttonType',
                        fieldClass: 'col-6 row  j-c-end pt-4 border-top',
                        name: 'save',
                        type: 'button',
                        class: 'btn btn-secondary',
                        text: 'Save',
                    }
                ]
    },
    boardFields() {
        this.crud.form = null;
        this.$store.dispatch('loading');
        this.$store.dispatch('onebb/get', { resource: 'category' }).then(response => {
        let options = [];
        response['hydra:member'].forEach(function(el) {
            options.push({
                val: '/api/categories/' + el.id,
                name: el.name
            });
        });
        
        this.crud.form = [
            {
                fieldType: 'selectType',
                fieldClass: 'col-12 column my-1',
                val: this.resources.category,
                name: 'category',
                class: 'form-control my-1',
                label: 'Select board category',
                options: options
            },
            {
                fieldType: 'inputType',
                fieldClass: 'col-12 column my-1',
                name: 'name',
                val: this.resources.name,
                type: 'text',
                class: 'form-control my-1',
                label: 'Board name',
            },
            {
                fieldType: 'inputType',
                fieldClass: 'col-12 column my-1',
                val: this.resources.priority ?? 0,
                name: 'priority',
                type: 'number',
                class: 'form-control m-1',
                label: 'Board priority',
            },
            {
                fieldType: 'joditType',
                fieldClass: 'col-12 column my-1',
                name: 'description',
                val: this.resources.description ?? '',
                type: 'textarea',
                class: 'form-control my-1',
                label: 'Board description',
            },
            {
                fieldType: 'checkboxType',
                fieldClass: 'col-12 my-1 row j-c-center a-i-center',
                checked: this.resources.active,
                name: 'active',
                class: 'form-control my-1',
                label: 'Active',

            },
            {
                 fieldType: 'buttonType',
                fieldClass: 'col-6 row pt-4 border-top',
                name: 'submit',
                type: 'button',
                class: 'btn btn-secondary',
                text: 'Save and exit',
            },
            {
                fieldType: 'buttonType',
                fieldClass: 'col-6 row  j-c-end pt-4 border-top',
                name: 'save',
                type: 'button',
                class: 'btn btn-secondary',
                text: 'Save',
            }
        ]
        this.$store.dispatch('loaded');
        })  
    },    
    formEvents: function(formData) {
        this.$store.dispatch('onebb/put', { 
            id: this.$route.params.id,  
            resource: this.$route.params.resource,
            data: formData.fields
        }).then(response => {
            if (response['@type'] === "hydra:Error") {
                this.show = true;
                this.modal = {
                    success: false,
                    error: true,
                    message: 'Something went wrong!',
                }
                setTimeout(() => {
                    this.show = false;
                }, 3000);
                return;
            }
        
            this.show = true;
            this.modal = {
                success: true,
                error: false,
                message: this.$route.params.resource +' save',
            }
            setTimeout(() => {
                this.show = false;
                if (formData.action === 'submit') {
                    this.$router.push({ name: 'ForumList' });
                }
            }, 3000);
           
        });
        
    }
  },
  components: {
    Update,
    Modal
  },
  mounted() {
    this.reloadCrud()     
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

