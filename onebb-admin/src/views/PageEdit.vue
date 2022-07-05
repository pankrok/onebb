<template>
  <div class="row" :key="$route.name">
  <div class="col-12 my-3 pt-1 mx-1">
     <router-link 
        :to="{ name: 'PagesList' }" 
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
  name: 'PageEdit',
  data() {
    return {
        resources: null,
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
                            fieldClass: 'col-10 column my-1',
                            name: 'name',
                            val: this.resources.name,
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Name',
                    }, 
                    {
                            fieldType: 'checkboxType',
                            fieldClass: 'col-2 row d-flex j-c-center a-i-center py-1',
                            checked: this.resources.active,
                            name: 'active',
                            class: 'form-control m-1',
                            label: 'Active',
      
                    },
                    {
                            fieldType: 'joditType',
                            fieldClass: 'col-12 column my-1',
                            name: 'content',
                            val: this.resources.content,
                            type: 'jodit',
                            class: 'form-control m-1',
                            label: 'Content',
                    },
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 row j-c-end my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Update page',
     
                    }
                ]
                
        this.$store.dispatch('loaded');
    },    
    formEvents: function(formData) {
        
        this.$store.dispatch('onebb/put', { 
            id: this.$route.params.id,  
            resource: this.$route.params.resource,
            data: formData.fields
        }).then(response => {
            console.log(response);
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

