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
        group: [],
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
                name: role, // FIXME here should be used translations!
                val: role,
                selected: this.resources.roles.includes(role)
            };
             
            this.userRoles.push(r);
        });
    
    
        this.crud.form = null;
        this.$store.dispatch('loading');
        this.crud.form = [
                {
                    fieldType: 'inputType',
                    fieldClass: 'col-6 column my-1',
                    name: 'username',
                    val: this.resources.username,
                    type: 'text',
                    class: 'form-control m-1',
                    label: 'Username',
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
                    fieldClass: 'col-12 column my-1',
                    val: this.resources.user_group.id,
                    name: 'default_gruop',
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
                    options: this.userRoles,
                    class: 'form-control m-1',
                    label: 'Roles',
  
                },
                                {
                    fieldType: 'checkboxType',
                    fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1',
                    checked: this.resources.verified,
                    name: 'verified',
                    val: this.resources.verified,
                    class: 'form-control m-1',
                    label: 'Verified',
  
                },
                {
                    fieldType: 'checkboxType',
                    fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1',
                    checked: this.resources.banned,
                    name: 'banned',
                    val: this.resources.banned,
                    class: 'form-control m-1',
                    label: 'Banned',
  
                },
                {
                    fieldType: 'checkboxType',
                    fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1',
                    checked: this.resources.acp_enable,
                    name: 'acpEnable',
                    val: this.resources.acp_enable,
                    class: 'form-control m-1',
                    label: 'ACP',
  
                },
                {
                    fieldType: 'checkboxType',
                    fieldClass: 'col-3 row d-flex j-c-center a-i-center py-1',
                    checked: this.resources.mcp_enable,
                    name: 'mcpEnable',
                    val: this.resources.mcp_enable,
                    class: 'form-control m-1',
                    label: 'MCP',
  
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
    formEvents: function(formData) { 
         if (formData.fields.password === null) {
            delete formData.fields.password
        }
         
        if (formData.fields.roles === null) {
            delete formData.fields.roles
        }
        
    //    formData.fields['acpEnabled'] = formData.fields.apc_enabled;
    //    delete formData.fields.apc_enabled;
    //      delete formData.fields.undefined; 
        this.$store.dispatch('onebb/put', { 
            id: this.$route.params.id,  
            resource: 'userAdmin', 
            data: formData.fields
        })
    }
  },
  components: {
    Update
  },
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'group' }).then(response => {
        let options = [];
        response['hydra:member'].forEach(function(el) {
            options.push({
                val:  el.id,
                name: el.group_name
            });
        });
        this.group = options;
        this.reloadCrud();  
    });
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }

}
</script>

