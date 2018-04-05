<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">


              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
                  <tr>
                      <td><bs-input label="Ticket_no" type="integer"  disabled :maxlength="255" :icon="true" v-model="formData.ticket_no"></bs-input></td> 
                      <td><bs-input label="QUOTE_ID" type="integer" disabled       :maxlength="255" :icon="true" v-model="formData.QUOTE_ID"></bs-input></td>
                      <td><bs-input label="ORDER_ID" type="text" disabled  :maxlength="255" :icon="true" v-model="formData.ORDER_ID"></bs-input></td>
                  </tr>

                    <tr>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="status">Status</label></div>
                                <Select clearable filterable v-model="formData.ticket_type_id"
                                        @on-change="onChangeStatus"  placeholder="Please select a status..."   style="width:180px"  >
                                    <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>
                    </tr>


                </tbody>
              </table>



                   
               <!--     <bs-input label="Ticket_type_id" type="integer" required  :maxlength="255" :icon="true" v-model="formData.ticket_type_id"></bs-input>  -->
                    
                    <bs-input label="location_id" type="integer" required  :maxlength="255" :icon="true" v-model="formData.location_id"></bs-input>
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
    {  computed: {   ...mapState({  showModal: state => state.csticket.showModal,
                                    cs1ticketActivityData: state=> state.csticket.csticketActivityData,
                                    user: state => state.authUser,
                                    selectedOrder: state => state.tab.selectedOrder,
                                    csTicketlast: state => state.csticket.csTicketlast,
                     
                                    
               //so now cpm1Activity of this file(defined below)= value from store cpm1 file
                                  }),
                        order() {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
                   },
       data ()  {  return {  title: '',  formData: {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '',
                                                       location_id: '',
                                                       name: '',  title: '',  id: ''  ,orderStatus: '',
                                                     },
                                                     statusOptions: [],    
                            }   
                },
       created() {  console.log('/OL/cscrud---created.')  
                     //this.$store.dispatch('getOrderStatusOptions')
                     this.$store.dispatch('gettickettypetable')
                    .then((response) =>
                        { console.log('/booking/OL/cscrud-created getOrderStatusOptions success=', response);
                          this.setOrderStatusOptions(response.data);
                        })
                     .catch((error) => {  console.error('/booking/OL/cscrud--getAssignedLocationOptions error=', error);     });

       
                  },
       components: {  'custom-modal': modal, 'bs-input': input,  },
       mounted() { console.log('/OL/cscrud-- mounted. testData=', this.testData) },
       methods: 
           {    onChangeStatus(val) { console.log('/OL/cscrud---onStatusLocation val=',val);    },
                setOrderStatusOptions(statuses) 
                     {  console.log('/OL/cscrud---setOrderStatusOptions statuses=',statuses);
                        let options = [];
                        for (let status in statuses) 
                         { options.push({value: statuses[status].id, label: statuses[status].ticket_type});  }
                           this.statusOptions = options;
                     },
                OnSave() //---------------on save while adding and edit----coming from action=Add in  onClickNew() in statelistview
                { console.log('/OL/cscrud-----OnSave_click');
                  //let payload = {  isShow: false,  data: this.formData, };
                 let payload = {  isShow: false,  data: this.formData, };
                  if (this.cs1ticketActivityData.action === 'Add')// add new state
                     { console.log('/OL/cscrud-----inside add');
                         //console.log('/OL/cscrud-----inside add:',cs1ticketActivityData);
                        // console.log('/OL/cscrud-----inside csactivitydata:',csticketActivityData.data);
                     //console.log('/OL/cscrud-----inside cs1activitydata:',cs1ticketActivityData.data);
                     console.log('/OL/cscrud-----inside formdata:',this.formData);
                     console.log('/OL/cscrud-----inside payload:',payload);
                     this.$store.dispatch('setCsTicketShowModal', payload); 
                        this.$store.dispatch('addCsTicketRequest', this.formData)
                               .then((response) => 
                               {   //this.$store.dispatch('setCsTicketShowModal', payload);
                                   console.log('/OL/csticketcrud.vue--addCpmActivityRequest fire events -> refreshCpmActivityTable');
                                  // this.$events.fire('refreshCpmActivityTable');
                               })
                              .catch((error) => {});
                       // .then((response) => {})     .catch((error) => {});
                     } 
                  else if (this.testData.action === 'Edit')// update
                   { this.$store.dispatch('setTestShowModal', payload);  
                     this.$store.dispatch('updateTestRequest', this.formData)
                        .then((response) => {})     .catch((error) => {});
                   }
                  else  {  console.log('/OL/cscrud-----error after add');   }// error
                },
            onClose() 
            {  console.log('/OL/cscrud---onClose');  //-----when add popup comes---once u leave it or close it
                //it should again tell the value to store
                let payload = {  isShow: false,  data: { data: null},  };
                this.$store.dispatch('setCsTicketShowModal', payload);
                this.resetFormData();
            },
               resetFormData() {  this.formData = { ticket_no: '',
                                                    ticket_type_id: '',
                                                    QUOTE_ID: '',
                                                    ORDER_ID: '',
                                                    location_id: '',
                                                    name: '',  
                                                    title: '', 
                                                    id: ''  
                                                }; 
                                }

           },
           watch: 
           {  cs1ticketActivityData(newVal, oldVal) //this function name is defined in computed and its value taken from store
                    {   console.log('/OL/CsticketCrud.vue--++++++cs1ticketActivityData changed newVal=', newVal);
                        console.log('/OL/CsticketCrud.vue--+++++cs1ticketActivityData changed oldVal=', oldVal);
                        console.log('/OL/CsticketCrud.vue--+++++csticketActivityData =', this.csticketActivityData);
                        console.log('/OL/CsticketCrud.vue--+++ticket no1 =', this.csTicketlast);
                        if (this.cs1ticketActivityData && this.cs1ticketActivityData.action === 'Add')
                            {   this.resetFormData();  this.title = 'Add New Ticket';  
                                console.log('/OL/CsticketCrud.vue--+++ticket no2 =', this.csTicketlast);
                                this.formData.QUOTE_ID=this.selectedOrder.QUOTE_ID;
                                this.formData.ORDER_ID=this.selectedOrder.UDF1;
                                var aa=this.csTicketlast.id; aa++;   aa = String(aa).trim(); 
                                this.formData.ticket_no=aa;
                            
                            }
                //csticketActivityData  is defined in store file and imported here
                    },
            },
          
    }

    </script>