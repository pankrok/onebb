<template>
<div v-if="resources" class="box col-12">
    <div class="box-header row j-c-space-between a-i-center">
    <h1>{{ crud.name }} {{ $route.params.resource }}<span v-if="resources.name"> - {{ resources.name }}</span></h1>
     </div>
        <div class="box-content row j-c-center a-i-center">
            <div v-for="(field, index) in crud.form" :class="field.fieldClass" :key="field.fieldType + index"> 
                <component :is="field.fieldType" :cfg="field" @update:form-el="callback"/>
            </div>
        </div>
</div>
</template>

<script>
import Input from './formControl/Input'; 
import Textarea from './formControl/Textarea'; 
import Button from './formControl/Button';  
import Checkbox from './formControl/Checkbox';
import Select from './formControl/Select';
import Jodit from './formControl/Jodit';

export default {
  name: 'Update',
  props: {
    crud: Object,
    resources: Object,
    fields: Object,
  },
  data() {
    return {
        data: {},
        formData: {}
    }
  },
  methods: {
    callback: function(v) {
        if (v.name === "submit" || v.val === "button") {
            this.$emit('update:formEvent', 
                {
                    action: v.name,
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
    joditType: Jodit,
    textareaType: Textarea,
  },
  emits: ['update:formEvent']
}
  </script>