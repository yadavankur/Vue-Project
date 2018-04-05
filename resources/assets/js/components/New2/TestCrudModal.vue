<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="State Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.title"></bs-input>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-success" @click="onClose">Cancel</button>
                <button type="button" class="btn btn-primary" @click="OnSave">Save</button>
            </div>
        </custom-modal>
    </div>
</template>
<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import input from 'vue-strap/src/Input'
    export default 
    {  computed: 
           { ...mapState({ showModal: state => state.new2.showModal, testData: state=> state.new2.testData,  }),   },
       data ()  {  return {  title: '',  formData: {     name: '',    title: '',   id: '', }    }   },
       created() {  console.log('components/New2/TestCrudModal.vue-CustomModal Component created.')  
       
       //this.$store.dispatch('useraspergroupscascade');//----------------users
                 //   this.$store.dispatch('getAssignedCascadeLocationOptions')  // get user
       },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('components/New2/TestCrudModal.vue-CustomModal Component mounted. testData=', this.testData) },
       methods: 
           {
               OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('components/New2/TestCrudModal.vue---OnSave_click');
                  let payload = {  isShow: false,  data: this.formData, };
                  if (this.testData.action === 'Add')// add new state
                     { this.$store.dispatch('setTestShowModal', payload); 
                       this.$store.dispatch('addTestRequest', this.formData)
                        .then((response) => {})     .catch((error) => {});
                     } 
                  else if (this.testData.action === 'Edit')// update
                   { this.$store.dispatch('setTestShowModal', payload);  this.$store.dispatch('updateTestRequest', this.formData)
                        .then((response) => {})     .catch((error) => {});
                   }
                  else  {     }// error
                },
               onClose() {  console.log('components/New2/TestCrudModal.vue---onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setTestShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { name: '',  title: '',  id: ''  }; }

           },
           watch: {  testData() 
                      {  console.log('components/New2/TestCrudModal.vue-+++++++++++++ testData changed =', this.testData);
                         if (this.testData && this.testData.action === 'Add')  //this opens a form
                            {   this.resetFormData();   this.title = 'Adding a new test';  
                                console.log('components/New2/TestCrudModal.vue-+++form is open now_just before save is pressed');
                            }
                          else if (this.testData && this.testData.action === 'Edit')
                           {   this.resetFormData();  this.title = 'Editing the state';
                               this.formData.id = this.testData.data.id;
                               this.formData.name = this.testData.data.name;
                               this.formData.title = this.testData.data.title;
                           }
                       }
               }
    }

    </script>