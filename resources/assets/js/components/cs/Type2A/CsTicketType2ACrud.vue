<template>
    <div>
        <custom-modal :value="showFormType2A" @cancel="onClose" effect="fade">
             <div slot="modal-header" class="modal-header"><h4 class="modal-title"> {{ title }} </h4></div>

              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
              
   
                     <tr><td colspan="2">
                             <div class="form-group">
                                 <div><label for="status">APPROVING USER</label></div>
                                   <Cascader v-model="cascade_group_user" :data="cascadeUserOptions"
                                             size="large"  placeholder="Please select a Group/User..."
                                             @on-change="handleUserChange1" 
                                   > </Cascader>
                              </div>
                        </td>
                        <td >
                            <div class="form-group"><div><label for="status">STATUS</label></div>
                                <Select clearable filterable v-model="formData.status_id"
                                       size="large"  @on-change="onChangeStatus"  placeholder="Please select a status..."   >
                                    <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                               </Select>
                            </div>
                        </td>
                      
                        </tr>
                                ` <tr>
                        <td colspan="2">
                            <bs-input label="Reason" type="text" required  :maxlength="255" :icon="true" v-model="formData.reason"></bs-input>
                        </td>
                        <td >
                            <bs-input label="Price(AUD)" type="text" required  :maxlength="255" :icon="true" v-model="formData.price"></bs-input>
                         </td>
                    
                    </tr> 
             
              </tbody>
              </table>
                <bs-input label="Comments" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
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
                            cascadeUserOptions: state => state.cstkt.useraspgroup.groupNodes,
                        ticketcnstatustable: state => state.csticketcnstatus.ticketcnstatustable,
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
       data ()  {  return {  title: '',  formData: {     id: '', comment: '', 
                            price: '', description: '', ticket_no: '', status_id: '' } ,statusOptions: [] ,
                            cascade_group_user:[]   }   },
       created() {  console.log('cs/cstickettype1crud.vue-- Component created.')  
                                    
                    this.collectCnStatusOptions(this.ticketcnstatustable); //to collect cn status options for dropdown
                  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/cstickettype1crud.vue--- Component mounted. typeData=', this.type2Data) },
       methods: 
           {    
                    collectCnStatusOptions(statuses) 
                     {  console.log('CNstatuscrud-- statuses=',statuses);
                        let options = [];
                        for (let status in statuses) 
                         { options.push({value: statuses[status].id, label: statuses[status].STATUS});  }
                           this.statusOptions = options;
                     },
                    onChangeStatus(val) { console.log('CNstatuscrud-- statuses=-onStatusLocation val=',val);    },
                    handleUserChange1 (value, selectedData) //----change user
                            {  console.log('/csticket/crud.vue--handleUserChange value=', value);
                                console.log('/csticket/crud.vue--handleUserChange selectedData=', selectedData);
                                this.formData.user = {id:'', name:''};
                               this.formData.group = {id:'', name:''};
                                if (selectedData.length > 0)
                                {  this.formData.group = {id: selectedData[0].value, name: selectedData[0].label};
                                 this.formData.user = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                                }
                            },


               OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('2Acrud.vue-----OnSave_click this.formdata',this.formData);
                  let payload = {  isShow: false,  data: this.formData, };
                  if (this.type2Data.action === 'Add')// add new state
                     {   console.log('add--formdata----',this.formData);
                          if (  this.isEmpty(this.formData.price) )
                            { this.$store.dispatch('showErrorNotification', 'Please provie price !');   return;  }
                            if (  this.formData.status_id === "" )
                            {  this.$store.dispatch('showErrorNotification', 'Please select status_id '); return;  }
                           // if (  this.isEmpty(this.formData.group) || this.isEmpty(this.formData.user)  )
                           // {      this.$store.dispatch('showErrorNotification', 'Please select user !');
                           //     return;
                           // }
                         
                         this.$store.dispatch('setCsTicketType2AShowModal', payload); //---to disable popup
                       this.$store.dispatch('cstype2Aadd', this.formData)
                        .then((response) => {   console.log(' save success'); 
                                                this.$events.fire('refreshcsticket');
                                            }).catch((error) => {console.log('save error');});
                     } 
                  else if (this.type2Data.action === 'Edit')// update
                   { this.$store.dispatch('setCsTicketType2AShowModal', payload);  
                   console.log(' payload=',payload); 
                      this.$store.dispatch('updatetype2A', this.formData)
                        .then((response) => { console.log(' edit success');  this.$events.fire('refreshcsticket');})     
                        .catch((error) => {});
                   }
                  else  {     }// error
                },
               onClose() {  console.log('cs/cstickettype2Acrud.vue------onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setCsTicketType2AShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '', price: '',  user:{id:'', name:''}, group :{id:'', name:''}, status_id: '', 
                                  comment: '', reason: ''  };this.cascade_group_user=[]; 
                                }

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
                                this.formData.price = this.csticket[0].amount;
                                this.formData.reason = this.csticket[0].aaa;
                               this.formData.comment = this.csticket[0].comment;
                               this.formData.ticket_no = this.csticket[0].ticket_no;

                               this.formData.group = {  id : this.csticket[0].agroupid.id,
                                                         name: this.csticket[0].agroupid.name
                                                     };
                                this.formData.user = { id : this.csticket[0].auserid.id,
                                                name: this.csticket[0].name,
                                            };
                                this.cascade_group_user.push(this.formData.group.id);
                                this.cascade_group_user.push(this.formData.user.id);
                                 this.formData.status_id = this.csticket[0].tstatus? this.csticket[0].tstatus.id : '';
                           }
                       }
               }
    }

    </script>