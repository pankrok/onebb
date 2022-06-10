<template>
  <div class="row" :key="$route.name">
  <div class="col-12 my-3 pt-1 mx-1">
     <router-link 
        :to="{ name: 'UsersList' }" 
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
import roles from '../assets/roles.json'

export default {  
  name: 'UserEdit',
  data() {
    return {
        resources: null,
        userRoles: [],
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
            resource: 'userAdmin'
        }).then(response => {
            this.resources = response;
            this.userFields();

            this.$store.dispatch('loaded');
        });   
    },
    userFields() {
        roles.forEach((role) => {
            let r = {
                name: role, // FIXME here shoid be used translations!
                val: role,
                selected: this.resources.roles.includes(role)
            };
             
            this.userRoles.push(r);
        });
    
    
        this.crud.form = null;
        this.$store.dispatch('loading');
       /* this.$store.dispatch('onebb/get', { resource: 'category' }).then(response => {
        let options = [];
        response['hydra:member'].forEach(function(el) {
            options.push({
                val: '/api/categories/' + el.id,
                name: el.name
            });
        }); */
        this.crud.form = [
                {
                    fieldType: 'inputType',
                    fieldClass: 'col-3 column my-1',
                    name: 'username',
                    val: this.resources.username,
                    type: 'text',
                    class: 'form-control m-1',
                    label: 'Username',
                },
                {
                        fieldType: 'inputType',
                        fieldClass: 'col-3 column my-1',
                        name: 'password',
                        type: 'password',
                        class: 'form-control m-1',
                        label: 'Password',
                },
                {
                        fieldType: 'checkboxType',
                        fieldClass: 'col-2 row d-flex j-c-center a-i-center py-1',
                        checked: true,
                        name: 'verified',
                        val: this.resources.verfied,
                        class: 'form-control m-1',
                        label: 'Verified',
  
                },
                {
                        fieldType: 'checkboxType',
                        fieldClass: 'col-2 row d-flex j-c-center a-i-center py-1',
                        checked: false,
                        name: 'banned',
                         val: this.resources.banned,
                        class: 'form-control m-1',
                        label: 'Banned',
  
                },
                {
                    fieldType: 'checkboxType',
                    fieldClass: 'col-2 row d-flex j-c-center a-i-center py-1',
                    checked: false,
                    name: 'acpEnabled',
                     val: this.resources.apc_enabled,
                    class: 'form-control m-1',
                    label: 'ACP',
  
                },
                {
                        fieldType: 'selectType',
                        fieldClass: 'col-12 row d-flex j-c-center a-i-center py-1 border-bottom',
                        name: 'roles',
                        multiple: true,
                        styles: "width: 100%; height: 250px;",
                        options: this.userRoles,
                        class: 'form-control m-1',
                        label: 'Roles',
  
                },
                {
                        fieldType: 'buttonType',
                        fieldClass: 'col-12 row j-c-end my-1',
                        name: 'submit',
                        type: 'button',
                        class: 'btn btn-secondary',
                        text: 'Edit user',
 
                }
        ]
        this.$store.dispatch('loaded');
    },    
    formEvents: function(formData) { console.log(formData.fields.password);
        if (formData.fields.password === null) {
            delete formData.fields.password
        }
                
        this.$store.dispatch('onebb/put', { 
            id: this.$route.params.id,  
            resource: 'userAdmin',
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
    this.reloadCrud();  
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

