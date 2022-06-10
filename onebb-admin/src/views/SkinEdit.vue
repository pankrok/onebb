<template>
  <div class="row">
  
    <div class="col-12">
        <div class="my-1 d-flex j-c-space-between">
            <router-link 
                :to="{ name: 'SkinList' }" 
                class="btn btn-secondary"
             >
            <i class="fa-solid fa-angle-left"></i> Back
            </router-link>
            <button class="btn btn-success" @click="save">Save</button>
        </div>
    <div class="box-header text-center my-1">
        <div class="d-flex j-c-center a-i-center">
            <SelectType :cfg="selectCfg" @update:form-el="callback" />
        </div>
    </div>    
    </div>
    <div class="col-sm-8">
    <div class="row">
    <div class="col-12">
    <div class="box-header">
      <h3 class="text-center">Top modules</h3>
    </div>
          <draggable
            id="top"
            :list="modules.top"
            class="list dropzone"
            group="modules-group"
            item-key="name"
          >
         
            <template #header>
                <span class="dz-message">Drop top modules here:</span>
              </template>
            <template #item="{ element }">
              <div class="box item m-1 p-1 text-center">
   
                    {{ element.name }}
              
              </div>
            </template>

          </draggable>


      </div>
    <div class="col-3">
        <div class="box-header">
            <h3 class="text-center">Left modules</h3>
        </div>
      <draggable
        id="left"
        data-source="juju"
        :list="modules.left"
        class="list dropzone"
        group="modules-group"
        item-key="name"
      >
            <template #header>
                <span class="dz-message">Drop left modules here:</span>
              </template>
        <template #item="{ element }">
          <div class="box item m-1 p-1 text-center">
            {{ element.name }}
            
          </div>
        </template>

        
      </draggable>
    </div>
    <div class="col-6" style="height: 100%;">
        <div class="box-header">
            <h3 class="text-center">Forum content</h3>
        </div>
        <div class="list dropzone m-1 p-1 bg-content">
            <div class="dz-message f-black">
               
            </div>
        </div>
    </div>
    <div class="col-3">
    <div class="box-header">
      <h3 class="text-center">Right modules</h3>
    </div>
      <draggable 
        id="right"
        :list="modules.right" 
        class="list dropzone" 
        group="modules-group" 
        item-key="name"
     >
     
        <template #header>
                <span class="dz-message">Drop right modules here:</span>
              </template>
        <template #item="{ element }">
          <div class="box item m-1 p-1 text-center">
            {{ element.name }}
          </div>
        </template>

       
      </draggable>
    </div>
    <div class="col-12">
    <div class="box-header">
      <h3 class="text-center">Bottom modules</h3>
    </div>
          <draggable
            id="bottom"
            :list="modules.bottom"
            class="list dropzone"
            group="modules-group"
            item-key="name"
          >
         
             <template #header>
                <span class="dz-message">Drop bottom modules here:</span>
              </template>
            <template #item="{ element }">
              <div class="box item m-1 p-1 text-center">
                {{ element.name }}
              </div>
            </template>
            

          </draggable>


      </div>
    <rawDisplayer class="col-2" :value="list" title="List" />
    <rawDisplayer class="col-2" :value="list2" title="List2" />
    <rawDisplayer class="col-2" :value="list3" title="List3" />
    <rawDisplayer class="col-2" :value="list4" title="List4" />
    </div>
  </div>
  <div class="col-sm-4">
    <div class="mx-1">
        <div class="fixed">
        <div class="box-header info">
            <h3 class="text-center f-black">Unused modules</h3>
        </div>
        <div class="d-flex j-c-center a-i-center my-1">
            <button class="btn btn-info w100" @click="newModule">Create new module</button>
        </div>
     <draggable
            id="modules-menu"
            :list="modules.unused"
            class="list dropzone"
            group="modules-group"
            item-key="name"
          >
         
             <template #header>
                
                <span class="dz-message">Drop modules here</span>
             </template>
             
            <template #item="{ element }">
              <div class="box item m-1 p-1 text-center row j-c-space-between a-i-center">
                <div>
                </div>
                <div>{{ element.name }}</div>
                <div>
                    <button v-if="element.engine == 'CustomBox'" class="btn btn-info" @click="edit(element)"><i class="fa-solid fa-pen-to-square"></i></button>
                </div>
                
              </div>
            </template>
            
            

          </draggable>
          </div>
    </div>
  </div>
  <Transition name="fade">   
    <Create 
        :data="modalData" 
        :fields="fields" 
        v-if="modal"
        v-model:show="modal" 
        @update:form-event="formData"   
    />
  </Transition>
</div>
</template>

<script>
import draggable from "vuedraggable";
import SelectType from "../components/crud/formControl/Select";
import Create from "../components/crud/Create";

export default {
  name: "SkinEdit",
  components: {
    draggable,
    SelectType,
    Create,
  },
  data() {
    return {
        def: null,
        currentPage: 'Home', 
        selectCfg: {
          name: 'PageSelect',
          val: 'Home',
          class: 'form-control btn btn-secondary my-1',
          label: 'Currently edited layout:',
          labelClass: 'mx-2',
          options: [
            {val: 'Home', name: 'Home', selected: true},
            {val: 'Category', name: 'Category', selected: false},
            {val: 'Board', name: 'Board', selected: false},
            {val: 'Plot', name: 'Plot', selected: false},
            {val: 'NewPlot', name: 'New Plot', selected: false},
            {val: 'SignUp', name: 'SignUp', selected: false},
            {val: 'Profile', name: 'Profile', selected: false},
            {val: 'Userlist', name: 'User list', selected: false},
            {val: 'Validation', name: 'Validation', selected: false}
          ],
      },
      modules: {
          unused: [],    
          top: [],
          bottom: [],
          left: [],
          right: []
      },
      modal: false,
      modalData: {
        action: 'createBox',
      },
      fields: []
    };
  },
  watch: {
    currentPage(newPage, oldPage) {
      if (newPage !== oldPage) {
        this.init();
      }
    }
  },
  methods: {
    save() {
        this.$store.dispatch('loading');
        this.$store.dispatch('onebb/post', {
            resource: 'skinBoxes',
            subresource: 'bulk',
            data: {
                id: this.$route.params.id,
                page: this.currentPage,
                modules: this.modules
            }
        }).then(() => {
            this.$store.dispatch('loaded');
        });
        
        
    },
    
    newModule() {
                this.fields = [
                {
                    fieldType: 'inputType',
                    name: 'id',
                    type: 'hidden',
                    val: null,
                },
                {
                    fieldType: 'inputType',
                    fieldClass: 'col-12 column my-1',
                    name: 'name',
                    type: 'text',
                    val: null,
                    class: 'form-control m-1',
                    label: 'Box name',
                },
                {
                    fieldType: 'joditType',
                    fieldClass: 'col-12 column my-1',
                    name: 'html',
                    type: 'jodit',
                    val: null,
                    class: 'form-control my-1',
                    label: 'Html code',
                },
                {
                    fieldType: 'buttonType',
                    fieldClass: 'col-12 row j-c-end my-1',
                    name: 'submit',
                    type: 'button',
                    class: 'btn btn-secondary',
                    text: 'Save',
 
                }
            ];
        this.modalData.action = 'onebb/post';
        this.modal = true;
    },
    
    edit(module) {
        this.fields = [
                {
                    fieldType: 'inputType',
                    name: 'id',
                    type: 'hidden',
                    val: module.id,
                },
                {
                    fieldType: 'inputType',
                    fieldClass: 'col-12 column my-1',
                    name: 'name',
                    type: 'text',
                    val: module.name,
                    class: 'form-control m-1',
                    label: 'Box name',
                },
                {
                    fieldType: 'joditType',
                    fieldClass: 'col-12 column my-1',
                    name: 'html',
                    type: 'jodit',
                    val: module.html,
                    class: 'form-control my-1',
                    label: 'Html code',
                },
                {
                    fieldType: 'buttonType',
                    fieldClass: 'col-12 row j-c-end my-1',
                    name: 'submit',
                    type: 'button',
                    class: 'btn btn-secondary',
                    text: 'Save',
 
                }
            ];
        this.modalData.action = 'onebb/put';
        this.modal = true;
    },
    
    formData(val) {
        this.$store.dispatch(val.action, {
            id: val.fields.id ?? null,
            resource: 'box',
            data: val.fields
        }).then(response => {
            if (val.action === 'onebb/post') {
                this.modules.unused.push(response);
            }
        });        
        this.modal = false;
    },
    
    callback(v) {
        this.currentPage = v.val;
    },
    
    updateModuels(all, page) {
    
        this.modules = {
              unused: [],    
              top: [],
              bottom: [],
              left: [],
              right: []
          }
    
        all.forEach((val, key) => {
            page.forEach((v) => {
                if (val['@id'] === v.box['@id'] && v.page === this.currentPage) {
                    this.modules[v.position].push(val);
                    delete all[key];
                }                
            })         
        });
        
        let filtered = all.filter(function (el) {
          return el != null;
        });
        
        if (filtered.length > 0 ) {
            filtered.forEach((val) => {
                this.modules.unused.push(val);
            });
        }
        
         this.$store.dispatch('loaded');
    },
    
    init() {
        this.$store.dispatch('loading');
        let allModules = {};
        let pageModules = {};
        // get all modules
        this.$store.dispatch('onebb/get', {resource: 'box'})
        .then(response => {
            allModules = response['hydra:member'];
            
            // get current skin modules
            this.$store.dispatch('onebb/get', 
                {
                    resource: 'skinBoxes',
                    params: [
                        {
                            param: 'skin',
                            value: '/skin/' + this.$route.params.id
                        }
                    ]
                }
            )
            .then(response => {
                pageModules = response['hydra:member'];
                this.updateModuels(allModules, pageModules);              
            })
            
        });
    }
    
  },
  mounted() {   
    this.init();
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  }
};
</script>
<style scoped>

.dropzone {
    background: var(--light);
    border-radius: 5px;
    border: 2px dashed rgb(0, 135, 247);
      border-image-outset: 0;
      border-image-repeat: stretch;
      border-image-slice: 100%;
      border-image-source: none;
      border-image-width: 1;
    border-image: none;
    min-height: 5rem;
    position: relative;
}

.bg-content {
    background: repeating-linear-gradient(
  45deg,
  var(--light),
  var(--light) 10px,
  var(--dark) 10px,
  var(--dark) 20px
);
}

.bg-dark {
    background: var(--dark);
}

.fixed {
    position: fixed;
}

#modules-menu {
    height: 293px;
    overflow: auto;
}

.w100 {
    width:100%;
}

.dz-message {
    text-align: center;
    position: absolute;
    width:100%;
    margin: 2rem 0;
    color: var(--dark);
    z-index: 1;
}

.item {
    position: relative;
    z-index: 2;
    cursor: -webkit-grab; 
    cursor: grab;
}

.f-black {
   color: var(--black); 
}

@media (min-width: 768px) {
    .fixed {
        position: relative;
    }
}

</style>
    