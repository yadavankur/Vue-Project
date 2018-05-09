<template>
    <div>
        <custom-modal :value="showFormType5" @cancel="onClose" effect="fade">
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
         <tr></tr>    
         
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
           { ...mapState({  showFormType5: state => state.cstickettype.showFormType5,//---------add---6
                            type5Data: state=> state.cstickettype.csticketType5data,  
                            selectedTicket: state => state.cstkt.selectedTicket,
                            cascadeUserOptions: state => state.cstkt.useraspgroup.groupNodes,
                            ticketcnstatustable: state => state.csticketcnstatus.ticketcnstatustable,
                            ticketerrortypetable: state => state.csticketerror.ticketerrortypetable,
                            selectedTicketttype1: state => state.cstkt.selectedTicket.ttype5,
                            csType1perTicket: state => state.cstickettype.csType5perTicket,
                            v6itemstable: state => state.cstkt.selectedTicket.v6items,
                            terrortypes: state => state.cstkt.selectedTicket.terrortype,
                        }), 
                    csticket() 
                        {  console.log('/t4/- this.selectedTicketttype4=',this.selectedTicketttype1); 
                           if (this.csType1perTicket )  
                              { if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                                   {  console.log('/3t/ this.csType1perTicket[0].ttype4', this.csType1perTicket[0].ttype5);
                                      return this.csType1perTicket[0].ttype5;
                                   }
                                 else  return this.selectedTicketttype1;
                              } 
                            else if (this.selectedTicketttype1)  
                             {  console.log('this.selectedTicketttype4=',this.selectedTicketttype1); 
                                return this.selectedTicketttype1;
                             } 
                            else return null; 
                        },  
                       itemlen()
                        { console.log('this.selectedTicket.v6items.length=',this.selectedTicket.v6items.length);
                        return this.selectedTicket.v6items.length;
                        
                        },
                        
            },
       data ()  {  return {  title: '',  formData: {     id: '', comment: '', item1_id : '', item2_id : '', allitems : '', allerrors: '', notes: '',
                            price: '', description: '', ticket_no: '', status_id: '', finds: [], find: '' , allitems2: '', allerrors2:'', allnotes2: ''} ,statusOptions: [] ,
                            cascade_group_user:[] , finds: []   }   },
       created() {  console.log('cs/cstickettype1crud.vue-- Component created.')      
                    this.collectCnStatusOptions(this.ticketcnstatustable); //to collect cn status options for dropdown
                    this.collectItemOptions(this.v6itemstable);
                    this.collectErrorOptions(this.terrortypes); 
                 },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('cs/cstickettype1crud.vue--- Component mounted. typeData=', this.type2Data) },
       methods: 
           {   
               
               addFind: function () {   let len= this.selectedTicket.v6items.length;
                                        let findslen=Object.keys(this.formData.finds).length;
                                        console.log('items len=',len);
                                        console.log('finds len=', findslen);
                                        if(findslen<=len-1)
                                        this.formData.finds.push({ label: '' }); 
                                        else this.$store.dispatch('showErrorNotification', 'enough of that- Max no of items reached!');  
                                    },
               
                collectCnStatusOptions(statuses) 
                     {  console.log('Type5Crud--- statuses=',statuses);
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
                onChangeStatus(val) { console.log('Type5Crud-onStatusChange val=',val);    },
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
                    {   console.log('add--formdata.finds----',this.formData.finds);
                           // var allitems1= ` 1.${this.formData.item1_id}`; 
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
                        if (this.type5Data.action === 'Add')// add new state
                        {   console.log('add--formdata----',this.formData);
                           this.$store.dispatch('setCsTicketType5ShowModal', payload); //---to disable popup
                           this.$store.dispatch('cstype5add', this.formData)
                           .then((response) => {   console.log(' save success'); 
                                              this.$events.fire('refreshcsticket');
                                          }).catch((error) => {console.log('save error');});
                        } 
                      else if (this.type5Data.action === 'Edit')// update
                        { this.$store.dispatch('setCsTicketType5ShowModal', payload);  
                          console.log(' payload=',payload); 
                          this.$store.dispatch('updatetype5', this.formData)
                          .then((response) => { console.log(' edit success');  this.$events.fire('refreshcsticket');})     
                          .catch((error) => {});
                        }
                      else  {     }// error
                    },
               onClose() {  console.log('cs/cstickettype5crud.vue------onClose');
                           let payload = {   isShow: false,   data: this.formData,   };
                           this.$store.dispatch('setCsTicketType5ShowModal', payload);
                           this.resetFormData();
                        },
               resetFormData() {  this.formData = { id: '', allitems: '', allerrors2:'', allerrors:'', price: '',  user:{id:'', name:''}, group :{id:'', name:''}, status_id: '', 
                                  comment: '', reason: '' , item1_id: '', item2_id: '', find: '', finds : [], allitems2: '', allnotes2: '', notes: ''  };this.cascade_group_user=[]; 
                                }
           }, //actions finish
           watch: {  type5Data() 
                      {  console.log('4crud/type5Data changed =', this.type5Data);
                         if (this.type5Data && this.type5Data.action === 'Add')  //this opens a form
                            {   this.resetFormData();   this.title = 'Add new SDA';  
                                console.log('cs/4crud.vue--+++form open -just before save is pressed');
                                this.formData.ticket_no = this.selectedTicket.ticket_no;
                                console.log('cs/4crud.vue--ticket_no', this.selectedTicket.ticket_no);
                            }
                         else if (this.type5Data && this.type5Data.action === 'Edit')
                           {   this.resetFormData();  this.title = 'Editing SDA';
                               console.log('cs/cstickettype1crud.vue--ticket_no', this.csticket);
                               this.formData.id = this.csticket[0].id;
                             
                         
                               this.formData.comment = this.csticket[0].comment;
                               this.formData.ticket_no = this.csticket[0].ticket_no;

                             //   this.formData.issues = this.csticket[0].issues;
                             //   this.formData.officeuse = this.csticket[0].officeuse;
                               this.formData.group = {  id : this.csticket[0].agroupid.id,
                                                         name: this.csticket[0].agroupid.name
                                                     };
                               this.formData.user = { id : this.csticket[0].auserid.id,
                                                      name: this.csticket[0].name,
                                                    };
                               this.cascade_group_user.push(this.formData.group.id);
                               this.cascade_group_user.push(this.formData.user.id);
                               this.formData.status_id = this.csticket[0].tstatus? this.csticket[0].tstatus.id : '';
                            //   this.formData.item1_id = this.csticket[0].bbb? this.csticket[0].bbb : '';
                                var abc1=this.csticket[0].aaa? this.csticket[0].aaa : '';
                                var abb1=this.csticket[0].bbb? this.csticket[0].bbb : '';
                                var abd1=this.csticket[0].ccc? this.csticket[0].ccc : '';
                                
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