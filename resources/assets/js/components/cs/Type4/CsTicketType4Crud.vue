<template>
    <div>
        <custom-modal :value="showFormType4" @cancel="onClose" effect="fade">
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
                    <tr> <div><label >Select Items to be picked: </label></div></tr>
                    <tr> <td colspan="2" >
                           <div><label for="status">ITEM-1</label></div>
                                <Select clearable filterable v-model="formData.item1_id"
                                       size="large"  @on-change="onChangeItems"  placeholder="Please select an Item..."   >
                                    <Option v-for="item in itemOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                               </Select>
                           
                        </td>
                          <td colspan="2">
                            <bs-input label="Notes (if any)" type="text" required  :maxlength="255" :icon="true" v-model="formData.reason"></bs-input>
                        </td>
                    </tr>
                  <tr>
                    <div v-if="formData.item1_id > 0">
                   <tr> <td colspan="2" >
                           <div><label for="status">ITEM-1</label></div>
                                <Select clearable filterable v-model="formData.item2_id"
                                       size="large"  @on-change="onChangeItems"  placeholder="Please select an Item..."   >
                                    <Option v-for="item in itemOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                               </Select>
                           
                        </td>

                    </tr>
                    </div>
                    <div v-if="formData.item2_id > 0">
                       <tr> <td colspan="2" >
                           <div><label for="status">ITEM-1</label></div>
                                <Select clearable filterable v-model="formData.item2_id"
                                       size="large"  @on-change="onChangeItems"  placeholder="Please select an Item..."   >
                                    <Option v-for="item in itemOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                               </Select>
                            
                        </td>

                    </tr>
                    </div>
                    <tr>
                        <td colspan="3">
                            <bs-input label="Issues To Be Addressed" type="textarea" :maxlength="400" required :icon="true" v-model="formData.issues"></bs-input>
                        </td>                    
                    </tr> 
                  <td colspan="3">
                            <bs-input label="Office user only(Scoper)" type="textarea" :maxlength="400" required :icon="true" v-model="formData.officeuse"></bs-input>
                        </td>                    
                 
             
              </tbody>
              </table>
                <bs-input label="Comments" type="textarea" :maxlength="400" :icon="true" v-model="formData.comment"></bs-input>
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
           { ...mapState({  showFormType4: state => state.cstickettype.showFormType4,//---------add---6
                            type3Data: state=> state.cstickettype.csticketType3data,  
                            selectedTicket: state => state.cstkt.selectedTicket,
                            cascadeUserOptions: state => state.cstkt.useraspgroup.groupNodes,
                            ticketcnstatustable: state => state.csticketcnstatus.ticketcnstatustable,
                            selectedTicketttype1: state => state.cstkt.selectedTicket.ttype3,
                            csType1perTicket: state => state.cstickettype.csType3perTicket,
                            v6itemstable: state => state.cstkt.selectedTicket.v6items,
                        }), 
                    csticket() 
                        {  console.log('/t3/- this.selectedTicketttype3=',this.selectedTicketttype1); 
                           if (this.csType1perTicket )  
                              { if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                                   {  console.log('/3t/ this.csType1perTicket[0].ttype3', this.csType1perTicket[0].ttype3);
                                      return this.csType1perTicket[0].ttype3;
                                   }
                                 else  return this.selectedTicketttype1;
                              } 
                            else if (this.selectedTicketttype1)  
                             {   console.log('this.selectedTicketttype3=',this.selectedTicketttype1); 
                                return this.selectedTicketttype1;
                             } 
                            else return null; 
                        },  
                       itemlen()
                        { console.log('this.selectedTicket.v6items.length=',this.selectedTicket.v6items.length);
                        return this.selectedTicket.v6items.length;
                        
                        },
                        
            },
       data ()  {  return {  title: '',  formData: {     id: '', comment: '', item1_id : '', item2_id : '',
                            price: '', description: '', ticket_no: '', status_id: '' } ,statusOptions: [] ,
                            cascade_group_user:[]   }   },
       created() {  console.log('cs/cstickettype1crud.vue-- Component created.')      
                    this.collectCnStatusOptions(this.ticketcnstatustable); //to collect cn status options for dropdown
                    this.collectItemOptions(this.v6itemstable);
                 },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/cstickettype1crud.vue--- Component mounted. typeData=', this.type2Data) },
       methods: 
           {        collectCnStatusOptions(statuses) 
                     {  console.log('Type4Crud--- statuses=',statuses);
                        let options = [];
                        for (let status in statuses) 
                         { options.push({value: statuses[status].id, label: statuses[status].STATUS});  }
                           this.statusOptions = options;
                     },
                     collectItemOptions(statuses) 
                     {  console.log('Type4Crud--- Items=',statuses);
                        let options = [];
                      //  for (let status in statuses) 
                        for (let i=0;i< statuses.length; i++) 
                         { var ss=statuses[i];
                             options.push({value: ss.QTE_POS, label: ss.FRA_CODE});  
                         }
                           this.itemOptions = options;
                            console.log('Type4Crud--- ItemOptions=',this.itemOptions);
                     },
                    onChangeStatus(val) { console.log('Type4Crud-onStatusChange val=',val);    },
                     onChangeItems(val) { console.log('Type4Cruds=-onItemsChange val=',val);    },
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
                    {   console.log('3crud.vue-----OnSave_click this.formdata',this.formData);
                        let payload = {  isShow: false,  data: this.formData, };
                        if (this.type4Data.action === 'Add')// add new state
                        {   console.log('add--formdata----',this.formData);
                              
                            this.$store.dispatch('setCsTicketType4ShowModal', payload); //---to disable popup
                            this.$store.dispatch('cstype4add', this.formData)
                            .then((response) => {   console.log(' save success'); 
                                                this.$events.fire('refreshcsticket');
                                            }).catch((error) => {console.log('save error');});
                        } 
                      else if (this.type4Data.action === 'Edit')// update
                        { this.$store.dispatch('setCsTicketType4ShowModal', payload);  
                          console.log(' payload=',payload); 
                          this.$store.dispatch('updatetype4', this.formData)
                          .then((response) => { console.log(' edit success');  this.$events.fire('refreshcsticket');})     
                          .catch((error) => {});
                        }
                      else  {     }// error
                    },
               onClose() {  console.log('cs/cstickettype4crud.vue------onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setCsTicketType4ShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '', price: '',  user:{id:'', name:''}, group :{id:'', name:''}, status_id: '', 
                                  comment: '', reason: '' , item1_id: '', item2_id: '' };this.cascade_group_user=[]; 
                                }
           }, //actions finish
           watch: {  type3Data() 
                      {  console.log('3crud/type3Data changed =', this.type3Data);
                         if (this.type3Data && this.type3Data.action === 'Add')  //this opens a form
                            {   this.resetFormData();   this.title = 'Add new Rectification Report';  
                                console.log('cs/3crud.vue--+++form open -just before save is pressed');
                                this.formData.ticket_no = this.selectedTicket.ticket_no;
                                console.log('cs/3crud.vue--ticket_no', this.selectedTicket.ticket_no);
                            }
                         else if (this.type3Data && this.type3Data.action === 'Edit')
                           {   this.resetFormData();  this.title = 'Editing Rectification Report';
                               console.log('cs/cstickettype1crud.vue--ticket_no', this.csticket);
                               this.formData.id = this.csticket[0].id;
                             
                               this.formData.reason = this.csticket[0].aaa;
                               this.formData.comment = this.csticket[0].comment;
                               this.formData.ticket_no = this.csticket[0].ticket_no;

                                this.formData.issues = this.csticket[0].issues;
                                this.formData.officeuse = this.csticket[0].officeuse;
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