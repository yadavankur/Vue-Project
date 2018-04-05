<template>
    <div>
        <custom-modal :value="showPopup" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="STATUS" type="text" required  :maxlength="255" :icon="true" v-model="formData.STATUS"></bs-input>
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
    {  computed: 
           { ...mapState({ showPopup: state => state.csticketstatus.showPopup,//---------add---6
                          statusData: state=> state.csticketstatus.statusData,  
                        }),   
            },
       data ()  {  return {  title: '',  formData: {     id: '',    STATUS: '',   comment: '', }    }   },
       created() {  console.log('/cs/csstatus/CsStatusCrudModal.vue---- created.')  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('/cs/csstatus/CsStatusCrudModal.vue--- mounted. statusData=', this.statusData) },
       methods: 
           {
               OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('/cs/csstatus/CsStatusCrudModal.vue----OnSave_click'); //-------add----9
                  let payload = {  isShow: false,  data: this.formData, }; //isshow false after save pressed
                  if (this.statusData.action === 'Add')// add new state
                     { this.$store.dispatch('csstatusshowpopup', payload); //this is to disable popup after save pressed ie  isshow- false
                       this.$store.dispatch('csstatusadd', this.formData) //--add-10--send the status form fields to store file
                        .then((response) => {})     .catch((error) => {});
                     } 
                  else if (this.statusData.action === 'Edit')// edit---8
                   { 
                       this.$store.dispatch('csstatusshowpopup', payload);  
                       this.$store.dispatch('updatestatus', this.formData)
                        .then((response) => {})     .catch((error) => {});
                   }
                  else  {     }// error
                },
               onClose() {  console.log('/cs/csstatus/CsStatusCrudModal.vue-----onClose'); ////---------add---8
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('csstatusshowpopup', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '',  STATUS: '',  comment: ''  }; }

           },
           watch: {  statusData() 
                      {  console.log('/cs/csstatus/CsStatusCrudModal.vue-+++++++ statusData changed =', this.statusData);
                         if (this.statusData && this.statusData.action === 'Add')  ////---------add---7
                            {   this.resetFormData();   this.title = 'Adding New Status';  
                                console.log('/cs/csstatus/CsStatusCrudModal.vue-+++add form is open now_just before save is pressed');
                            }
                          else if (this.statusData && this.statusData.action === 'Edit')//-----edit 7
                           {   this.resetFormData();  
                               this.title = 'Editing the STATUS';
                               console.log('/cs/csstatus/CsStatusCrudModal.vue-+++edit form is open now_just before save is pressed');
                               this.formData.id = this.statusData.data.id;
                               this.formData.STATUS = this.statusData.data.STATUS;
                               this.formData.comment = this.statusData.data.comment;
                           }
                       }
               }
    }

    </script>