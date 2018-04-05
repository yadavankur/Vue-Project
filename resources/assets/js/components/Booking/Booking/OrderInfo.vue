<template>
    <div id="root-order-info">
        <div class="app-row">
            <change-address-modal
                    :isShowChangeAddress="isShowChangeAddress"
                    :order="selectedOrder? selectedOrder : null"
                    @onCloseChangeAddressModal="onCloseChangeAddressModal">
            </change-address-modal>
            <new-supervisor-modal
                    :isShowNewSupervisor="isShowNewSupervisor"
                    :order="selectedOrder? selectedOrder : null"
                    @onCloseNewSupervisorModal="onCloseNewSupervisorModal">
            </new-supervisor-modal>
            <change-supervisor-modal
                    :isShowChangeSupervisor="isShowChangeSupervisor"
                    :order="selectedOrder? selectedOrder : null"
                    @newSupervisor="newSupervisor"
                    @selectSupervisor="selectSupervisor"
                    @onCloseChangeSupervisorModal="onCloseChangeSupervisorModal">
            </change-supervisor-modal>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#orderinfo"> Order Information </a>
                </div>
                <div id="orderinfo" class="panel-collapse collapse in table-responsive">
                    <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                        <tbody>
                        <tr><td>Sales Order Number</td>
                            <td colspan="2">
                                <Input v-model="orderNo" style="width: 360px" @on-enter="onSearch">
                                    <Select clearable
                                            filterable
                                            :value ="getLocationId(order? order.QUOTE_NUM_PREF: '')"
                                            slot="prepend"
                                            @on-change="onChangeLocation"
                                            placeholder="Please select a location..."
                                            style="width:180px"
                                    >
                                        <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                    </Select>
                                    <Button slot="append" icon="ios-search" @click="onSearch"></Button>
                                </Input>
                            </td>
                        </tr>
                        <tr><td>Sales Quote Number</td><td colspan="2"> {{order? order.QUOTE_NUM : ''}}</td></tr>
                        <tr><td>Customer</td><td>{{ order && order.customer ? order.customer.CUST_NAME : '' }}</td><td>{{ order && order.customer ? order.customer.CUST_CODE : '' }}</td> </tr>
                        <tr><td>Customer Tier</td><td colspan="2">{{ order && order.tier? (order.tier.name) : '' }}</td></tr>
                        <tr><td>Dowell Sales Contact</td><td colspan="2">{{ order && order.sales_person ? order.sales_person.USER_NAME : ''  }} </td> </tr>
                        <tr><td >Delivery Address</td> <td colspan="2">
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
                        <tr><td>Colour</td><td colspan="2">{{ color? color.DESCR : ''}}</td> </tr>
                        <tr> <td>Supervisor From V6 </td>
                            <td colspan="2">{{ order && order.contact ? order.contact.CONTACT_NAME + ' '
                                + (order.contact.MOBILE?order.contact.MOBILE:order.contact.PHONE) : ''}}
                            </td>
                        </tr>
                        <tr><td>Supervisor Updated  </td>
                            <td colspan="2">
                                <button v-if="order" type="button" class="btn btn-warning btn-xs" @click="onClickChangeSupervisor" title="Change Supervisor">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </button>
                                {{ order && order.contact ? order.contact.CONTACT_NAME + ' '
                                + (order.contact.MOBILE?order.contact.MOBILE:order.contact.PHONE)  : ''}}
                            </td>
                        </tr>
                        <tr> <td>Supervisor Contact Email </td><td colspan="2">{{ order && order.contact ? order.contact.EMAIL_ADDR : ''}}</td></tr>
                        <tr><td>Status </td> <td colspan="2">{{ order && order.status? order.status.DESCR : ''}} </td> </tr>
                       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'
    import ChangeSupervisorModal from './ChangeSupervisor/ChangeSupervisorModal.vue'
    import NewSupervisorModal from './ChangeSupervisor/NewSupervisorModal.vue'
    import ChangeAddressModal from './ChangeAddress/ChangeAddressModal.vue'

    export default 
    { methods: 
        {   newAddress() { console.log('/booking/OrderInfo.vue-newAddress'); },
            selectAddress() {  console.log('/booking/OrderInfo.vue-selectAddress');   },
            onClickChangeAddress() 
            {   console.log('/booking/OrderInfo.vue-onClickChangeAddress');
                this.onOpenChangeChangeAddressModal();
            },
            onOpenChangeChangeAddressModal() 
            {   console.log('/booking/OrderInfo.vue-onOpenChangeChangeAddressModal');
                this.isShowChangeAddress = true;
            },
            onCloseChangeAddressModal(value) 
            {   console.log('/booking/OrderInfo.vue-onCloseChangeAddressModal value=', value);
                this.isShowChangeAddress = false;
                if (value)
                { this.refreshOrderDetail(); }// todo
            },
            getLocationAbbr(locationId) 
            {   let locationAbbr = '';
                if (locationId == '') return locationAbbr;
                this.locationOptions.forEach( item => 
                {   if (item.value == locationId)
                    { locationAbbr = item.abbr; }
                });
                return locationAbbr;
            },
            getLocationId(locationAbbr) 
            {   let locationId = '';
                if (locationAbbr == '') return locationId;
                this.locationOptions.forEach( item => 
                {  if (item.abbr == locationAbbr)  { locationId = item.value; }
                });
                return locationId;
            },
            selectSupervisor(dataItem) 
            {   console.log('/booking/OrderInfo.vue-OrderInfo -> selectSupervisor. dataItem=', dataItem);
                // change the supvisor
                let formData = 
                {   quoteId: this.order? this.order.QUOTE_ID: '',
                    quoteVers: this.order? this.order.QUOTE_VERS: '',
                    contactId: dataItem.CONTACT_ID,
                };
                console.log('/booking/OrderInfo.vue-OrderInfo -> selectSupervisor formData=', formData);
                // send update request
                this.$store.dispatch('changeSupervisorRequest', formData)
                    .then((response) => 
                    {   console.log('/booking/OrderInfo.vue-changeSupervisorRequest response=', response);
                        // refresh the booking page
                        this.onCloseChangeSupervisorModal();
                        this.onSearch();
                    })
                    .catch((error) => 
                    {    console.log('/booking/OrderInfo.vue-changeSupervisorRequest error=', error);
                    });
            },
            newSupervisor() 
            {   console.log('/booking/OrderInfo.vue-OrderInfo -> newSupervisor.');
                this.onOpenNewSupervisorModal();
            },
            onClickChangeSupervisor() {
                console.log('/booking/OrderInfo.vue-onClickChangeSupervisor.');
                this.onOpenChangeSupervisorModal();
            },
            onSearch() 
            {   console.log('/booking/OrderInfo.vue-onSearch orderNo=', this.orderNo);
                console.log('/booking/OrderInfo.vue-onSearch location=', this.location);
                // check validation
                if (this.isEmpty(this.orderNo))
                {   this.$store.dispatch('showErrorNotification', 'Please input the sales order number!');
                    return;
                }
                if (!this.location)
                {   this.$store.dispatch('showErrorNotification', 'Please input the location!');
                    return;
                }
                let payload = {  orderNo: this.orderNo, location: this.location, };
                this.$emit('onSearch', payload); //------emit to parent vue file ie Booking.vue
            },
            onOpenChangeSupervisorModal() 
            {   console.log('/booking/OrderInfo.vue-onOpenChangeSupervisorModal');
                this.isShowChangeSupervisor = true;
            },
            onCloseChangeSupervisorModal() 
            {   console.log('/booking/OrderInfo.vue-onCloseChangeSupervisorModal');
                this.isShowChangeSupervisor = false;
            },
            onOpenNewSupervisorModal() 
            {   console.log('/components/booking/booking/OrderInfo.vue-onOpenNewSupervisorModal');
                this.isShowNewSupervisor = true;
            },
            onCloseNewSupervisorModal(value) 
            {   console.log('/booking/OrderInfo.vue-onCloseNewSupervisorModal');
                this.isShowNewSupervisor = false;
                if (value == 'newSuccess')  // close supervisor list modal as well
                {   this.onCloseChangeSupervisorModal();  this.onSearch(); }
            },
            onChangeLocation(selVal) 
            {   console.log('/booking/OrderInfo.vue-onChangeLocation selVal=', selVal);
                this.location = this.getLocationAbbr(selVal);
            },
            setAssignedLocationOptions(locations) 
            {   console.log('/booking/OrderInfo.vue-setAssignedLocationOptions locations=',locations);
                let options = [];
                for (let location in locations) 
                {  options.push({ value: locations[location].id, label: locations[location].name,
                                  abbr: locations[location].abbreviation,
                                });
                }
                this.locationOptions = options;
            },
            refreshOrderDetail() 
            {  let payload = {  orderNo: this.selectedOrder.UDF1,
                                quoteId: this.selectedOrder.QUOTE_ID,
                                location: this.selectedOrder.QUOTE_NUM_PREF,
                             };
                this.$emit('refreshOrderDetail', payload);
            },
        },
        computed: 
        {   ...mapGetters({    }),
            ...mapState({    }),
            order() {  if (this.selectedOrder)  return this.selectedOrder;  else return null;  },
            color() {  if (this.details && this.details.color) return this.details.color; else return null;   }

        },
        components: 
        {   'bs-input': input,
            'change-supervisor-modal': ChangeSupervisorModal,
            'new-supervisor-modal': NewSupervisorModal,
            'change-address-modal': ChangeAddressModal,
        },
        props: { user: {    type: Object,  default: null,  },
                selectedOrder: {  type: Object,  default: null,  },
                details: {  type: Object, default: null, },
               },
        data () { return {  orderNo: '', location: '', isShowChangeSupervisor: false,
                          isShowNewSupervisor: false, locationOptions: [], isShowChangeAddress: false,
                         }
                },
        created() 
              {   if (this.order)   this.orderNo = this.order.UDF1; //get option list
                  this.$store.dispatch('getAssignedLocationOptions')
                  .then((response) => 
                    {   console.log('/booking/OrderInfo.vue-created getAssignedLocationOptions success=', response);
                        this.setAssignedLocationOptions(response.data);
                    })
                  .catch((error) => { console.error('/booking/OrderInfo.vue-getAssignedLocationOptions error=', error);  });
              }
    }
</script>
<style scoped>
    .form-group {  margin-bottom: 0px !important;  }
    .input-group { height: 0px !important;  margin-bottom: 0px !important;  }
    .ivu-select-item { text-align: left !important;   }
</style>
