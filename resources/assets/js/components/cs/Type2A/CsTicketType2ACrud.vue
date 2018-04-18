<template>
    <div>
        <custom-modal :value="showFormType2A" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                 
                    <bs-input label="Price" type="text" required  :maxlength="255" :icon="true" v-model="formData.price"></bs-input>
                    <bs-input label="Comments" type="text" required  :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
                    <Checkbox v-model="formData.isFull">Need Full Day?</Checkbox>
                    <Checkbox v-model="formData.tick_option">Need Manual Confirm?</Checkbox>
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
           { ...mapState({ showFormType2A: state => state.cstickettype.showFormType2A,//---------add---6
                           type2Data: state=> state.cstickettype.csticketType2Adata,  
                            selectedTicket: state => state.cstkt.selectedTicket,

                        selectedTicketttype1: state => state.cstkt.selectedTicket.ttype2a,
                        csType1perTicket: state => state.cstickettype.csType2AperTicket,
                        }), 
                        csticket() {  console.log('/2a/- this.selectedTicketttype1=',this.selectedTicketttype1); 
                        if (this.csType1perTicket )  
                              { 
                                if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                                {     console.log('/2a/ this.csType1perTicket[0].ttype1', this.csType1perTicket[0].ttype2a);
                                      return this.csType1perTicket[0].ttype2a;
                                }
                                else  return this.selectedTicketttype1;
                              } 
                        else if (this.selectedTicketttype1)  
                            {   console.log('this.selectedTicketttype1=',this.selectedTicketttype1); 
                                return this.selectedTicketttype1;
                            } 
                        else return null; 
                     },  
            },
       data ()  {  return {  title: '',  formData: {     id: '', comment: '', price: '', description: '', ticket_no: '' }    }   },
       created() {  console.log('cs/cstickettype1crud.vue-- Component created.')  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/cstickettype1crud.vue--- Component mounted. typeData=', this.type2Data) },
       methods: 
           {
               OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('cs/cstickettype2Acrud.vue-----OnSave_click');
                  let payload = {  isShow: false,  data: this.formData, };
                  if (this.type2Data.action === 'Add')// add new state
                     { this.$store.dispatch('setCsTicketType2AShowModal', payload); //---to disable popup
                       this.$store.dispatch('cstype2Aadd', this.formData)
                        .then((response) => {console.log(' save success'); 
                        this.$events.fire('refreshcsticket');
                        console.log(' setselectedticket--this.selectedTicket=',this.selectedTicket); 
                                          })     .catch((error) => {console.log('save error');});
                     } 
                  else if (this.type2Data.action === 'Edit')// update
                   { this.$store.dispatch('setCsTicketType2AShowModal', payload);  
                   console.log(' payload=',payload); 
                      this.$store.dispatch('updatetype1', this.formData)
                        .then((response) => {})     .catch((error) => {});
                   }
                  else  {     }// error
                },
               onClose() {  console.log('cs/cstickettype2Acrud.vue------onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setCsTicketType2AShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '',  ticket_type: '',  sla_time: '', comment: ''  }; }

           },
           watch: {  type2Data() 
                      {  console.log('cs/cstickettype1crud.vue-++++++ type2Data changed =', this.type2Data);
                         if (this.type2Data && this.type2Data.action === 'Add')  //this opens a form
                            {   this.resetFormData();   this.title = 'Add new Credit Note';  
                                console.log('cs/cstickettype1crud.vue--+++form is open now_just before save is pressed');
                                this.formData.ticket_no = this.selectedTicket.ticket_no;
                                console.log('cs/cstickettype1crud.vue--ticket_no', this.selectedTicket.ticket_no);
                            }
                          else if (this.type2Data && this.type2Data.action === 'Edit')
                           {   this.resetFormData();  this.title = 'Editing Credit Note';
                               console.log('cs/cstickettype1crud.vue--ticket_no', this.csticket);
                               this.formData.id = this.csticket[0].id;
                                this.formData.price = this.csticket[0].price;
                               this.formData.comment = this.csticket[0].comment;
                           }
                       }
               }
    }

    </script>