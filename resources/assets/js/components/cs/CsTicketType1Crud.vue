<template>
    <div>
        <custom-modal :value="showFormType1" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Changes" type="text" required  :maxlength="255" :icon="true" v-model="formData.changes"></bs-input>
                    <bs-input label="Price" type="text" required  :maxlength="255" :icon="true" v-model="formData.price"></bs-input>
                    <bs-input label="Special Details" type="text" required  :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>

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
           { ...mapState({ showFormType1: state => state.cstickettype.showFormType1,//---------add---6
                           type1Data: state=> state.cstickettype.csticketType1data,  
                            selectedTicket: state => state.cstkt.selectedTicket,
                        }),   
            },
       data ()  {  return {  title: '',  formData: {     id: '',  ticket_type: '',  sla_time: '', comment: '' }    }   },
       created() {  console.log('cs/cstickettype1crud.vue-- Component created.')  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/cstickettype1crud.vue--- Component mounted. typeData=', this.type1Data) },
       methods: 
           {
               OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('cs/cstickettype1crud.vue-----OnSave_click');
                  let payload = {  isShow: false,  data: this.formData, };
                  if (this.type1Data.action === 'Add')// add new state
                     { this.$store.dispatch('setCsTicketType1ShowModal', payload); //---to disable popup
                       this.$store.dispatch('cstype1add', this.formData)
                        .then((response) => {console.log(' save success'); 
                        this.$events.fire('refreshcsticket');
                        console.log(' setselectedticket--this.selectedTicket=',this.selectedTicket); 
                                          })     .catch((error) => {console.log('save error');});
                     } 
                  else if (this.type1Data.action === 'Edit')// update
                   { this.$store.dispatch('setCsTicketType1ShowModal', payload);  
                   console.log(' payload=',payload); 
                      this.$store.dispatch('cstype1update', this.formData)
                        .then((response) => {})     .catch((error) => {});
                   }
                  else  {     }// error
                },
               onClose() {  console.log('cs/cstickettype1crud.vue------onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setCsTicketType1ShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '',  ticket_type: '',  sla_time: '', comment: ''  }; }

           },
           watch: {  type1Data() 
                      {  console.log('cs/cstickettype1crud.vue-++++++ type1Data changed =', this.type1Data);
                         if (this.type1Data && this.type1Data.action === 'Add')  //this opens a form
                            {   this.resetFormData();   this.title = 'Add new Credit Note';  
                                console.log('cs/cstickettype1crud.vue--+++form is open now_just before save is pressed');
                                this.formData.ticket_no = this.selectedTicket.ticket_no;
                                console.log('cs/cstickettype1crud.vue--ticket_no', this.selectedTicket.ticket_no);
                            }
                          else if (this.type1Data && this.type1Data.action === 'Edit')
                           {   this.resetFormData();  this.title = 'Editing Credit Note';
                               this.formData.id = this.type1Data.data.id;
                               this.formData.changes = this.type1Data.data.description;
                               this.formData.price = this.type1Data.data.price;
                                this.formData.comment = this.type1Data.data.comment;
                           }
                       }
               }
    }

    </script>