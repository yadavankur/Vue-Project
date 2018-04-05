<template>
    <div>
        <custom-modal :value="showTypeCrudPopup" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Type Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.ticket_type"></bs-input>
                    <bs-input label="SLA Time" type="text" required  :maxlength="255" :icon="true" v-model="formData.sla_time"></bs-input>
                    <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
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
    {   computed: 
           { ...mapState({ showTypeCrudPopup: state => state.cstickettype.showTypeCrudPopup,//---------add---6
                           typeData: state=> state.cstickettype.typeData,  
                        }),   
            },
       data ()  {  return {  title: '',  formData: {     id: '',  ticket_type: '',  sla_time: '', comment: '' }    }   },
       created() {  console.log('cs/csttype/crud.vue-- Component created.')  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/csttype/crud.vue- Component mounted. typeData=', this.typeData) },
       methods: 
           {
               OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('cs/csttype/crud.vue----OnSave_click');
                  let payload = {  isShow: false,  data: this.formData, };
                  if (this.typeData.action === 'Add')// add new state
                     { this.$store.dispatch('cstypeshowpopup', payload); //---to disable popup
                       this.$store.dispatch('cstypeadd', this.formData)
                        .then((response) => {})     .catch((error) => {});
                     } 
                  else if (this.typeData.action === 'Edit')// update
                   { this.$store.dispatch('cstypeshowpopup', payload);  
                      this.$store.dispatch('updatetype', this.formData)
                        .then((response) => {})     .catch((error) => {});
                   }
                  else  {     }// error
                },
               onClose() {  console.log('cs/csttype/crud.vue----onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('cstypeshowpopup', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '',  ticket_type: '',  sla_time: '', comment: ''  }; }

           },
           watch: {  typeData() 
                      {  console.log('cs/csttype/crud.vue-+++++++ typeData changed =', this.typeData);
                         if (this.typeData && this.typeData.action === 'Add')  //this opens a form
                            {   this.resetFormData();   this.title = 'Add new Ticket Type';  
                                console.log('cs/csttype/crud.vue--+++form is open now_just before save is pressed');
                            }
                          else if (this.typeData && this.typeData.action === 'Edit')
                           {   this.resetFormData();  this.title = 'Editing the state';
                               this.formData.id = this.typeData.data.id;
                               this.formData.ticket_type = this.typeData.data.ticket_type;
                               this.formData.sla_time = this.typeData.data.sla_time;
                               this.formData.comment = this.typeData.data.comment;
                           }
                       }
               }
    }

    </script>