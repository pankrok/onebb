<template>
  <div class="box col-12">
        <div class="box-header row j-c-space-between a-i-center">
            <h1>{{ crud.name }}</h1>
                <span>
                    <button 
                        v-for="button in crud.buttons" 
                        class="btn btn-sm mx-1" 
                        :class="button.class" 
                        :key="button.name"
                        @click="action(button.action, {resource: button.resource})"
                    >
                        {{ button.name }}
                    </button>
                </span>
        </div>
        
        <div class="content">
        <div class="box-content d-flex j-c-space-between a-i-center p-1 m-0">
                <div class="px-2"> <b>{{ crud.values.name }}</b></div>
                <div class="row a-i-center">
                    <div v-if="crud.values.checkbox" class="px-2 border-right">
                        <b><i class="fas fa-toggle-on"></i></b>
                    </div>
                    <div class="px-2">
                        <b><i class="fa-solid fa-gear"></i></b>
                    </div>
                </div>
            </div>
            <div v-for="values in resources" :key="values[crud.values.id]" :class="crud.listClass">
                <div :class="crud.itemListClass" class="d-flex j-c-space-between a-i-center m-0 py-1">
                    <span v-html="values[crud.values.name]"></span> 
                    <span class="row a-i-center">
                        <div v-if="crud.values.checkbox" class="onoffswitch mx-4" >
                            <input 
                                type="checkbox" 
                                name="'crud-switch-'+ values[crud.values.id]" 
                                class="onoffswitch-checkbox" 
                                :id="'crud-switch-'+ values[crud.values.id]" 
                                tabindex="0" 
                                v-model="values[crud.values.checkbox]"
                                @click.self="checkbox(values[crud.values.id], values[crud.type].toLowerCase(), values[crud.values.checkbox])"
                            >
                            <label class="onoffswitch-label" :for="'crud-switch-'+ values[crud.values.id]"></label>
                        </div>
                        <div class="relative" @click="optionSelect('main' + values[crud.values.id])">
                            <button class="btn btn-sm"><i class="fa-solid fa-ellipsis"></i></button>
                            <Transition name="slide-fade"> 
                            <div v-if="('main' + values[crud.values.id]) == editOption" class="box absolute options blue-shadow" :key="'main' + values[crud.values.id]">
                                <router-link 
                                    v-if="crud.edit" 
                                    :to="{ name: crud.editRoute, params: {id: values[crud.values.id], resource: values[crud.type].toLowerCase() } }" 
                                    class="btn btn-sm mx-1"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i> {{ $t('edit') }}
                                </router-link>
                                <button 
                                    v-if="crud.delete"
                                    @click="action('delete:itemDelete', {id: values[crud.values.id], resource: values[crud.type]})"
                                    class="btn danger btn-sm mx-1"
                                >
                                    {{ $t('delete') }}
                                </button>
                            </div>
                            </Transition>
                        </div>
                    </span>
                </div>
                <span v-if="crud.nested != null">
                <div v-for="nested in values[crud.values.nested]" :key="nested[crud.nested.id]" :class="crud.nested.class" class="list-item m-0 py-1">
                    <div class="col-12 d-flex j-c-space-between mx-0">
                        <span class="mx-2">
                            <!-- <input  -->
                                <!-- type="checkbox"  -->
                                <!-- :id="'nested-id-' + nested[crud.nested.id]"  -->
                                <!-- :name="'nested-' + nested[crud.nested.id]" -->
                            <!-- > -->
                            
                            {{ nested[crud.nested.name] }}
                        </span>
                        <span class="row a-i-center">
                            <div v-if="crud.nested.checkbox" class="onoffswitch mx-3"  >
                                <input 
                                    type="checkbox" 
                                    name="onoffswitch" 
                                    class="onoffswitch-checkbox" 
                                    :id="'nested-switch-'+ nested[crud.nested.id]" 
                                    tabindex="0" 
                                    v-model="nested[crud.nested.checkbox]"
                                    @click="checkbox(nested[crud.nested.id], nested[crud.type].toLowerCase(), nested[crud.values.checkbox])"
                                >
                                <label class="onoffswitch-label" :for="'nested-switch-'+ nested[crud.nested.id]"></label>
                            </div>
                            <div class="relative" @click="optionSelect('nested' + nested[crud.nested.id])">
                                <button class="btn btn-sm"><i class="fa-solid fa-ellipsis"></i></button>
                                <Transition name="slide-fade"> 
                                <div v-if="('nested' + nested[crud.nested.id]) == editOption" class="box absolute options blue-shadow" :key="'nested' + nested[crud.nested.id]">
                                    <router-link 
                                    v-if="crud.nested.edit" 
                                    :to="{ name: crud.editRoute, params: {id: nested[crud.nested.id], resource: nested[crud.type].toLowerCase() } }" 
                                    class="btn btn-sm mx-1"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i> {{ $t('edit') }}
                                    </router-link>
                                    <button 
                                        v-if="crud.nested.delete"
                                        @click="action('delete:itemNestedDelete', {id: nested[crud.nested.id], resource: nested[crud.type]})"
                                        class="btn danger btn-sm mx-1"
                                    >
                                        {{ $t('delete') }}
                                    </button>
                                </div>
                                </Transition>
                            </div>
                            
                        </span>
                    </div>
                </div>
                </span>
            </div>
        </div>
      </div>
      <Transition name="fade">   
        <component 
            :is="modalComponent" 
            :data="modalData" 
            :fields="fields" 
            v-if="modal"
            v-model:show="modal" 
            @update:form-event="formData" 
        />
      </Transition>
</template>

<script>
import Create from '../components/crud/Create.vue'; 
import Update from '../components/crud/Update.vue';  
import Delete from '../components/crud/Delete.vue';  

export default {
  name: 'Crud',
  props: {
    crud: Object,
    resources: Object,
    
  },
  data() {
    return {
        modal: false,
        modalComponent: null,
        editOption: null,
        modalData: {},
        fields: {}
    }
  },
  methods: {
    formData(val) {
        this.$emit('update:crudForm', val);
        this.modal = false;
    },
    optionSelect(id) {
        if (this.editOption !== id) {
            this.editOption = id;
        } else {
            this.editOption = null;
        }
    },
    action(action, data) {
        
        const handler = action.split(':');
        
        if (handler[0] !== 'delete') {
            this.fields = this.crud.forms[handler[1]];
        } else {
            this.fields = [
               
                {
                        fieldType: 'buttonType',
                        name: 'back',
                        type: 'button',
                        class: 'btn btn-info',
                        text: 'No',
 
                },
                {
                        fieldType: 'buttonType',
                        name: 'submit',
                        type: 'button',
                        class: 'btn btn-danger',
                        text: 'Yes',
 
                },
            ]
        }
        
        this.modalData = {
            action: handler[1],
            fields: data
        }; 
        this.modalComponent = handler[0]
        this.modal = true;        
    },
    checkbox(val, type, checked) {
        this.$emit('update:crudCheckbox', {
            id: val,
            resource: type,
            fields: {
                active: !checked
            }
        });    
    }    
  },
  components: {
    create: Create,
    update: Update,
    delete: Delete,
  },
  emits: ['update:crudForm']
}
</script>
<style scoped>
.options {
    width: 140px;
    height: 35px;
    right: 0;
    z-index: 10000;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>