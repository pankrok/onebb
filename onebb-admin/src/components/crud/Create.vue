<template>

<div 
    class="modal"
    @click.self="$emit('update:show', false)" 
>
    <div class="box mw-50">
        <div class="box-header text-center">
          <h4>{{ data.action }}</h4> 
        </div>   
        <div class="box-content row j-c-center a-i-center">
            <div v-for="(field, index) in fields" :class="field.fieldClass" :key="field.fieldType + index">
                <component :is="field.fieldType" :cfg="field" @update:form-el="callback"/>
            </div>
        </div>
    </div>
</div>
    


</template>
 

<script>

import Input from './formControl/Input'; 
import Button from './formControl/Button';  
import Checkbox from './formControl/Checkbox';
import Select from './formControl/Select';
import Jodit from './formControl/Jodit';

export default {
  name: 'CrudModal',
  props: {
    data: Object,
    fields: Object,
    show: Boolean,
  },
  data() {
    return {
        formData: {},
    }
  },
  methods: {
    callback: function(v) {
        if (v.name === "submit" || v.val === "button") {
            this.$emit('update:formEvent', 
                {
                    action: this.data.action,
                    button: v,
                    fields: this.formData
                });
            return;
        }
        this.formData[v.name] = v.val;
    }
  },
  components: {
    inputType: Input,
    buttonType: Button,
    checkboxType: Checkbox,
    selectType: Select,
    joditType: Jodit
  },
  emits: ['update:show', 'update:formEvent']
}
</script>