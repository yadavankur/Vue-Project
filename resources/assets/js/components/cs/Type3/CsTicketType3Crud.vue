<template>
    <div>
        <custom-modal :value="showFormType3" @cancel="onClose" effect="fade">
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


                        
                        <tr> <div><label >Error Caused by: ( Please tick box below)</label></div></tr>
                        <tr> <td colspan="3">
                          <Checkbox v-model="formData.builderorcustomer">Builder Or Customer</Checkbox>
                          <Checkbox v-model="formData.factory">Factory</Checkbox>
                          <Checkbox v-model="formData.service">Service</Checkbox>
                          <Checkbox v-model="formData.customerservice">Customer Service</Checkbox>
                          <Checkbox v-model="formData.sales">Sales</Checkbox>
                          <Checkbox v-model="formData.estimating">Estimating</Checkbox>
                          <Checkbox v-model="formData.transport">Transport</Checkbox>
                          <Checkbox v-model="formData.supplier">Supplier</Checkbox>
                          <Checkbox v-model="formData.other">Other</Checkbox>
                          </td>
                        </tr>
                   </tbody>
               </table>
                         <div v-for="find in formData.finds ">
                          <tr>     <td> <div><label for="status">ITEM</label></div>
                                <Select clearable filterable v-model="find.label"
                                       size="large"  @on-change="onChangeItems"  placeholder="Please select an Item..."   >
                                    <Option v-for="item in itemOptions" :value="item.label" :key="item" :label="item.label">{{ item.label }}</Option>
                               </Select></td>                              
                               <td>
                                 <div><label for="status">ERROR TYPE</label></div>
                                <Select clearable filterable v-model="find.error"
                                       size="large"  @on-change="onChangeError"  placeholder="Please select an ERROR..."   >
                                    <Option v-for="item in errorOptions" :value="item.label" :key="item" :error="item.label">{{ item.label }}</Option>
                               </Select></td>
                            
                                <td colspan="2">
                               <bs-input label="Notes" type="text" required  :maxlength="255" :icon="true" v-model="find.notes"></bs-input>
                              </td>
                        </tr>  
                </div>
                <button class="btn btn-primary btn-md" @click="addFind">
                    add Items
                </button>
 
                
              
            <bs-input label="Issues To Be Addressed" type="textarea" :maxlength="400" required :icon="true" v-model="formData.issues"></bs-input>
           <bs-input label="Office user only(Scoper)" type="textarea" :maxlength="400" required :icon="true" v-model="formData.officeuse"></bs-input>
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
           { ...mapState({  showFormType3: state => state.cstickettype.showFormType3,//---------add---6
                            type3Data: state=> state.cstickettype.csticketType3data,  
                            selectedTicket: state => state.cstkt.selectedTicket,
                            cascadeUserOptions: state => state.cstkt.useraspgroup.groupNodes,
                            ticketcnstatustable: state => state.csticketcnstatus.ticketcnstatustable,
                            selectedTicketttype1: state => state.cstkt.selectedTicket.ttype3,
                            csType1perTicket: state => state.cstickettype.csType3perTicket,
                            v6itemstable: state => state.cstkt.selectedTicket.v6items,
                            terrortypes: state => state.cstkt.selectedTicket.terrortype,
                            ticketerrortypetable: state => state.csticketerror.ticketerrortypetable,
                        }), 
                        csticket() {  console.log('/t3/- this.selectedTicketttype3=',this.selectedTicketttype1); 
                        if (this.csType1perTicket )  
                              { 
                                if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                                {     console.log('/3t/ this.csType1perTicket[0].ttype3', this.csType1perTicket[0].ttype3);
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
                    
            },
       data ()  {  return {  title: '',  formData: {     id: '', comment: '',finds: '',allitems: '', allerrors2:'', allerrors:'',
                            price: '', description: '', ticket_no: '', status_id: '' } ,statusOptions: [] ,
                            cascade_group_user:[]   }   },
       created() {  console.log('cs/cstickettype1crud.vue-- Component created.')      
                    this.collectCnStatusOptions(this.ticketcnstatustable); //to collect cn status options for dropdown
                    this.collectItemOptions(this.v6itemstable);
                    this.collectErrorOptions(this.terrortypes); 
                  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/cstickettype1crud.vue--- Component mounted. typeData=', this.type2Data) },
       methods: 
           {           addFind: function () {   let len= this.selectedTicket.v6items.length;
                                        let findslen=Object.keys(this.formData.finds).length;
                                        console.log('items len=',len);
                                        console.log('finds len=', findslen);
                                        if(findslen<=len-1)
                                        this.formData.finds.push({ label: '' }); 
                                        else this.$store.dispatch('showErrorNotification', 'enough of that- Max no of items reached!');  
                                    },
               
               
                   collectCnStatusOptions(statuses) 
                     {  console.log('CNstatuscrud-- statuses=',statuses);
                        let options = [];
                        for (let status in statuses) 
                         { options.push({value: statuses[status].id, label: statuses[status].STATUS});  }
                           this.statusOptions = options;
                     },
                       collectErrorOptions(statuses) 
                     {  console.log('Type5Crud--- errors=',statuses);
                        let options = [];
                        for (let i=0;i< statuses.length; i++) 
                         { var ss=statuses[i];
                             options.push({value: ss.id, label: ss.errorcode});  
                         }
                           this.errorOptions = options;
                           console.log('Type5Crud---errorOptions=',this.errorOptions);
                     },
                collectItemOptions(statuses) 
                     {  console.log('Type5Crud--- Items=',statuses);
                        let options = [];
                        for (let i=0;i< statuses.length; i++) 
                         { var ss=statuses[i];
                             options.push({value: ss.QTE_POS, label: ss.FRA_CODE});  
                         }
                           this.itemOptions = options;
                            console.log('Type5Crud--- ItemOptions=',this.itemOptions);
                     },
                    onChangeStatus(val) { console.log('CNstatuscrud-- statuses=-onStatusLocation val=',val);    },
                    onChangeError(val) { console.log('Type5Crud-onStatusChange val=',val);   },
                    onChangeItems(val) {  console.log('Type5Cruds=-onItemsChange val=',val);  },
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
                        var allitems1 = ''; var allerrors1 = ''; var allnotes1= '';
                              for (let i=0;i<= this.formData.finds.length-1; i++) 
                                {   if (this.formData.finds[i].label != '')
                                     allitems1= allitems1+`||${i}.`+ this.formData.finds[i].label; 
                                     else allitems1= allitems1+`||${i}.undefined`; 
                                    if (this.formData.finds[i].error != '')
                                     allerrors1= allerrors1+`||${i}.`+ this.formData.finds[i].error; 
                                     else    allerrors1= allerrors1+`||${i}.undefined`;
                                    if (this.formData.finds[i].notes != '')
                                     allnotes1= allnotes1+`||${i}.`+ this.formData.finds[i].notes;   
                                     else allnotes1= allnotes1+`||${i}.undefined`;
                                }

                          console.log('allitems1=',allitems1); console.log('allerrors1=',allerrors1); console.log('allerrors1=',allnotes1);
                          this.formData.allitems2=allitems1;
                          this.formData.allerrors2=allerrors1;
                          this.formData.allnotes2=allnotes1;
                          console.log('3crud.vue-----OnSave_click this.formdata',this.formData);
                       
                       
                       
                        let payload = {  isShow: false,  data: this.formData, };
                        if (this.type3Data.action === 'Add')// add new state
                        {   console.log('add--formdata----',this.formData);
                              //if (  this.isEmpty(this.formData.price) )
                              //  { this.$store.dispatch('showErrorNotification', 'Please provie price !');   return;  }
                               // if (  this.formData.status_id === "" )
                               // {  this.$store.dispatch('showErrorNotification', 'Please select status_id '); return;  }
                        
                            this.$store.dispatch('setCsTicketType3ShowModal', payload); //---to disable popup
                            this.$store.dispatch('cstype3add', this.formData)
                            .then((response) => {   console.log(' save success'); 
                                                this.$events.fire('refreshcsticket');
                                            }).catch((error) => {console.log('save error');});
                        } 
                      else if (this.type3Data.action === 'Edit')// update
                        { this.$store.dispatch('setCsTicketType3ShowModal', payload);  
                          console.log(' payload=',payload); 
                          this.$store.dispatch('updatetype3', this.formData)
                          .then((response) => { console.log(' edit success');  this.$events.fire('refreshcsticket');})     
                          .catch((error) => {});
                        }
                      else  {     }// error
                    },
               onClose() {  console.log('cs/cstickettype3crud.vue------onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setCsTicketType3ShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '', price: '',  user:{id:'', name:''}, group :{id:'', name:''}, status_id: '', 
                                  comment: '', reason: ''  , finds: [], allitems: '', allerrors2:'', allerrors:'' };this.cascade_group_user=[]; 
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

                                if(this.csticket[0].builderorcustomer==1) this.formData.builderorcustomer=true 
                                    else this.formData.builderorcustomer=false;
                               if(this.csticket[0].service==1) this.formData.service=true 
                                    else this.formData.service=false;
                               if(this.csticket[0].factory==1) this.formData.factory=true 
                                    else this.formData.factory=false;
                                if(this.csticket[0].customerservice==1) this.formData.customerservice=true 
                                    else this.formData.customerservice=false;
                                if(this.csticket[0].sales==1) this.formData.sales=true 
                                    else this.formData.sales=false;
                                if(this.csticket[0].estimating==1) this.formData.estimating=true 
                                    else this.formData.estimating=false;
                                if(this.csticket[0].transport==1) this.formData.transport=true 
                                    else this.formData.transport=false;
                                if(this.csticket[0].supplier==1) this.formData.supplier=true 
                                    else this.formData.supplier=false;
                                if(this.csticket[0].other==1) this.formData.other=true 
                                    else this.formData.other=false;

                                var abc1=this.csticket[0].ddd? this.csticket[0].ddd : '';
                                var abb1=this.csticket[0].ddd? this.csticket[0].eee : '';
                                var abd1=this.csticket[0].fff? this.csticket[0].fff : '';
                                
                                var abc2=abc1.split("||"); console.log('abc3.length=',abc2.length);
                                var abb2=abb1.split("||");  console.log('abc3.length=',abb2.length);
                                var abd2=abd1.split("||");  console.log('abc3.length=',abd2.length);
                                 for ( let j=1;j<abc2.length;j++)
                                 {  var abc3=abc2[j].split(".");
                                    var abb3=abb2[j].split(".");
                                    var abd3=abd2[j].split(".");
                                    this.formData.finds.push({ label: abc3[1],error: abb3[1] ,notes: abd3[1] }); 
                                 }



                           }
                       }
               }
    }

    </script>