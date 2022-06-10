<template>

<div 
    class="modal"
    @click.self="$emit('update:show', false)" 
>
    <div class="box danger mw-50">
        <div class="box-header text-center">
          <h4>{{ data.action }} - {{ data.fields.resource }} ID: {{ data.fields.id }}</h4>
        </div>   
        <div class="box-content row j-c-center a-i-center">
            <div class="col-12 text-center my-1">Are you sure you want to delete this item?</div>
            <div class="col-12 j-c-space-between row my-1">
            <div v-for="(field, index) in fields" :class="field.fieldClass" :key="field.fieldType + index">
                <component :is="field.fieldType" :cfg="field" @update:form-el="callback"/>
            </div>
            </div>
        </div>
    </div>
</div>
    


</template>
 

<script>

import Button from './formControl/Button';  

export default {
  name: 'Delete',
  props: {
    data: Object,
    fields: Object,
    show: Boolean,
  },
  methods: {
    callback: function(v) {
        if (v.name === "back") {
            this.$emit('update:show', false);
        }
    
        if (v.name === "submit") {
            this.$emit('update:formEvent', 
                {
                    action: this.data.action,
                    fields: {
                        resource: this.data.fields.resource.toLowerCase(),
                        id: this.data.fields.id
                    }
                });
            return;
        }
    }
  },
  components: {
    buttonType: Button,
  },
  emits: ['update:show', 'update:formEvent']
}
</script>