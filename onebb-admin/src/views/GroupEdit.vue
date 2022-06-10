<template>
  <div class="row" :key="$route.name">
  <div class="col-12 my-3 pt-1 mx-1">
     <router-link 
        :to="{ name: 'GroupList' }" 
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
            resource: 'group'
        }).then(response => {
            this.resources = response;
            this.groupFields();
        });   
    },
   
    groupFields() {
        this.crud.form = null;
        this.$store.dispatch('loading');
       
        this.crud.form = [
                               {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'htmlCode',
                            val: this.resources.html_code,
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Html code - you can use variable: {{username}} ',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'groupName',
                            val: this.resources.group_name,
                            type: 'text',
                            class: 'form-control m-1',
                            label: 'Group name',
                    },
                    {
                            fieldType: 'inputType',
                            fieldClass: 'col-12 column my-1',
                            name: 'groupLevel',
                            val: this.resources.group_level,
                            type: 'number',
                            class: 'form-control m-1',
                            label: 'Group level',
                    },
                    {
                            fieldType: 'buttonType',
                            fieldClass: 'col-12 row j-c-end my-1',
                            name: 'submit',
                            type: 'button',
                            class: 'btn btn-secondary',
                            text: 'Update group',
     
                    }
        ]
        this.$store.dispatch('loaded');

    },    
    formEvents: function(formData) {
        this.$store.dispatch('onebb/put', { 
            id: this.$route.params.id,  
            resource: 'group',
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
                message: 'Group save',
            }
            setTimeout(() => {
                this.show = false;
                if (formData.action === 'submit') {
                    this.$router.push({ name: 'GroupList' });
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

