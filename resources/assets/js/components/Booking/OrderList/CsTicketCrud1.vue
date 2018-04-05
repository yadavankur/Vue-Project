<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header"> <h4 class="modal-title"> {{ title }} </h4> </div>
            <div slot="modal-body" class="modal-body" v-if="showModal">
                <div class="form-group">
                    <div class="form-group">
<table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
                <tr> <td>Delivery Date</td>
                    <td colspan="2"> {{order? formatDate(order.DELIVERY_DATE, 'DD/MMM/YYYY') : ''}} </td>
                </tr>
                <tr><td>Location</td><td colspan="2"> {{order? order.QUOTE_NUM_PREF : ''}}</td></tr>
                  <tr><td>Sales Order Number</td><td colspan="2"> {{order? order.UDF1 : ''}}</td></tr>
                  <tr><td>Customer</td>
                    <td>{{ order && order.customer ? order.customer.CUST_NAME : '' }}</td>
                    <td>{{ order && order.customer ? order.customer.CUST_CODE : '' }}</td>
                  </tr>
                  <tr>
                    <td>Dowell Sales Contact</td>
                    <td colspan="2">{{ order && order.sales_person ? order.sales_person.USER_NAME : ''  }}</td>
                  </tr>                
                  <tr>
                    <td>Supervisor From V6</td>
                    <td colspan="2">{{ order && order.contact ? order.contact.CONTACT_NAME + ' ' + (order.contact.MOBILE?order.contact.MOBILE:order.contact.PHONE) : ''}}
                    </td>
                  </tr>
                  <tr>
                    <td>Supervisor Updated</td>
                    <td colspan="2">
                        <button v-if="order" type="button" class="btn btn-warning btn-xs" @click="onClickChangeSupervisor" title="Change Supervisor">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </button>
                        <!--<button v-if="order" type="button" class="btn btn-success btn-xs" @click="onClickChangeSupervisor">Change Supervisor</button>-->
                        {{ order && order.contact ? order.contact.CONTACT_NAME + ' '
                        + (order.contact.MOBILE?order.contact.MOBILE:order.contact.PHONE)  : ''}}
                    </td>
                  </tr>
                  <tr><td >Delivery Address</td>
                    <td colspan="2">
                        <button v-if="order" type="button" class="btn btn-warning btn-xs" @click="onClickChangeAddress" title="Change Address">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </button>
                        {{ order && order.deliver_address?
                                          (order.deliver_address.ADDR_1? order.deliver_address.ADDR_1 : '')
                                        + (order.deliver_address.ADDR_2? ' ' + order.deliver_address.ADDR_2: '')
                                        + (order.deliver_address.ADDR_3? ' ' + order.deliver_address.ADDR_3: '')
                                        + (order.deliver_address.ADDR_4? ' ' + order.deliver_address.ADDR_4: '')
                                        + (order.deliver_address.ADDR_5? ' ' + order.deliver_address.ADDR_5: '')
                                        + (order.deliver_address.ADDR_6? ' ' + order.deliver_address.ADDR_6: '')
                                        : ''
                        }}
                    </td>
                  </tr>
                 <tr><td>Status </td><td colspan="2">{{ order && order.status? order.status.DESCR : ''}} </td> </tr>
                </tbody>
              </table>
       <!--   above is just copied value-->       
               <Cascader v-model="cascade_location_service"
                                  :data="cascadeServiceOptions"
                                  size="large"
                                  :disabled="formData.id != ''"
                                  placeholder="Please select a service..."
                                  @on-change="handleChange"
                        >
                        </Cascader>
                    </div>
                    <div class="form-group">
                        <div><label for="group">Service Group</label></div>
                        <Select clearable
                                filterable
                                :value="service_group"
                                @on-change="onChangeServiceGroup"
                                placeholder="Please select a service group..."
                        >
                            <Option v-for="item in serviceGroupOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <bs-input label="Activity Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Activity Duration" type="text" required  :maxlength="10" :icon="true" v-model="formData.duration"></bs-input>
                    <Checkbox v-model="formData.isFull">Need Full Day?</Checkbox>
                    <Checkbox v-model="formData.tick_option">Need Manual Confirm?</Checkbox>
                    <!--<bs-input label="SQL Statement" type="textarea" :maxlength="10240" v-model="formData.sql_statement"></bs-input>-->
                    <div class="form-group">
                        <div><label for="sqlStatement">SQL Statement1</label></div>
                        <Input type="textarea"
                               :autosize="{minRows: 2,maxRows: 5}"
                               :maxlength="10240"
                               v-model="formData.sql_statement"></Input>
                    </div>
                    <div class="form-group">
                        <div><label for="actionSql">Action SQL</label></div>
                        <Input type="textarea":autosize="{minRows: 2,maxRows: 5}":maxlength="10240" v-model="formData.action_sql"></Input>
                    </div>
                    <div class="form-group">
                        <div><label for="comment">Comment</label></div>
                        <Input type="textarea":autosize="{minRows: 2,maxRows: 5}":maxlength="255" v-model="formData.comment"></Input>
                    </div>
                    <!--<bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>-->
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
    {   computed: {   ...mapState({  showModal: state => state.csticket.showModal,
                                     csticketActivityData: state=> state.csticket.csticketActivityData,
                                     user: state => state.authUser,
                                     selectedOrder: state => state.tab.selectedOrder,
               //so now cpm1Activity of this file(defined below)= value from store cpm1 file
                                  }),
                        order() {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
                   },
        props: {        },
        data () {  return {   title: '',
                             formData: {  name: '',  duration: '', isFull: true, tick_option: false,
                                         sql_statement: '',
                    action_sql: '',     comment: '',    id: '',   service_id: '',
                    service: {name:'', id:''},   service_group: '', state: {name:'', id:''},  location: {name:'', id:''},
                },
                cascadeServiceOptions: [],
                cascade_location_service: [],
                serviceGroupOptions: [],
                service_group: '',
            }
        },
        created() 
        {   console.log('/cpm1/Crud.vue--CustomModal Component created.');
            this.getCascadeLocationServices();
        },
        components: 
        {     'custom-modal': modal,     'bs-input': input,   },
                mounted() { console.log('/cpm1/Crud.vue--cpmActivityData=', this.cpmActivityData)    },
                beforeDestroy() 
                    {
                     console.log('/cpm1/Crud.vue-- beforeDestroy!');
                     let payload = {   isShow: false,   data: { data: null},      };
                     this.$store.dispatch('setCpmActivityShowModal', payload);
                    },
            methods: {    onChangeServiceGroup(value) 
                            {  console.log('/cpm1/Crud.vue--onChangeServiceGroup value=', value);
                                 this.formData.service_group = value;
                            },
                         getCpmServiceGroupOptions(payload, service_group) 
                           {    this.$store.dispatch('getCpmServiceGroupOptions', payload)
                                           .then((response) =>
                                     { console.log('/cpm1/Crud.vue-- getCpmServiceGroupOptions success=', response);
                                       this.setServiceGroupOptions(response.data.serviceGroups);
                                      if (service_group)  this.service_group = service_group;
                                    })
                                    .catch((error) => { console.error('/cpm1/Crud.vue-- getCpmServiceGroupOptions error=', error);  });
                            },
                        setServiceGroupOptions(groups) 
                             {  console.log('/cpm1/Crud.vue--setServiceGroupOptions groups=',groups);
                                let options = [];
                                for (let group in groups) {   options.push({value: parseInt(groups[group].value), label: groups[group].label});  }
                                this.serviceGroupOptions = options;
                            },
                         handleChange (value, selectedData) 
                           {
                                console.log('handleChange value=', value);
                                console.log('handleChange selectedData=', selectedData);
                                this.formData.state = {id:'', name:''};
                                this.formData.location = {id:'', name:''};
                                this.formData.service = {id:'', name:''};
                                 this.serviceGroupOptions = [];
                                 if (selectedData.length > 0)
                                     {
                                     //this.formData.state = {id: selectedData[0].value, name: selectedData[0].label};
                                      this.formData.location = {id: selectedData[0].value, name: selectedData[0].label};
                                      this.formData.service = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                                      let payload = {
                                      serviceId: this.formData.service.id,
                                      };
                                     this.getCpmServiceGroupOptions(payload);
                                    }
                            },
                       OnChanged(selVal) {   console.log('/cpm1/Crud.vue--OnChanged selVal=', selVal);    },
                      OnSave() 
                      {    console.log('/cpm1/Crud.vue--OnSave');
                           let payload = {   isShow: false,  data: { data: null},    };
                           if (this.cpmActivityData.action === 'Add')
                            {  this.$store.dispatch('addCpmActivityRequest', this.formData)
                               .then((response) => 
                               {   this.$store.dispatch('setCpmActivityShowModal', payload);
                                   console.log('/cpm1/Crud.vue--addCpmActivityRequest fire events -> refreshCpmActivityTable');
                                   this.$events.fire('refreshCpmActivityTable');
                               })
                              .catch((error) => {});
                            }
                            else if (this.cpmActivityData.action === 'Edit')
                            {    // update
                            this.$store.dispatch('updateCpmActivityRequest', this.formData)
                            .then((response) => 
                                {  this.$store.dispatch('setCpmActivityShowModal', payload);
                                 console.log('/cpm1/Crud.vue--updateCpmActivityRequest fire events -> refreshCpmActivityTable');
                                  this.$events.fire('refreshCpmActivityTable');
                                })
                           .catch((error) => {});
                           }
                      },
            onClose() 
            {  console.log('/OL/cscrud---onClose');  //-----when add popup comes---once u leave it or close it
                //it should again tell the value to store
                let payload = {  isShow: false,  data: { data: null},  };
                this.$store.dispatch('setCsTicketShowModal', payload);
                this.resetFormData();
            },
            resetFormData() 
              { this.formData = 
                  { name: '', duration: '', isFull: true, tick_option: false, sql_statement: '',
                    action_sql: '',  comment: '',
                    id: '',  service_id: '', state: {name:'', id:''},
                    location: {name:'', id:''}, service: {name:'', id:''}, service_group: '',
                  };
                this.cascade_location_service = [];
                this.serviceGroupOptions = [];
                this.service_group = '';
             }
        },
        watch: 
        {  csticketActivityData(newVal, oldVal) 
            {   console.log('/OL/CsticketCrud.vue--+++++++++++++ cpmActivityData changed newVal=', newVal);
                console.log('/OL/CsticketCrud.vue--+++++++++++++ cpmActivityData changed oldVal=', oldVal);
                console.log('/OL/CsticketCrud.vue--+++++++++++++ cpmActivityData changed =', this.csticketActivityData);
                if (this.csticketActivityData && this.csticketActivityData.action === 'Add')
                {  this.resetFormData();  this.title = 'Adding a new cpm1 activity';  }
                
            }
        }
    }
</script>
<style scoped>
</style>
