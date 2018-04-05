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
            <!-- Default panel contents -->
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#orderinfo">
                  Order Information
              </a>             
            </div>
            <div id="orderinfo" class="panel-collapse collapse in table-responsive">
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
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import { mapGetters, mapState, mapActions} from 'vuex'
import ChangeSupervisorModal from '../Booking/ChangeSupervisor/ChangeSupervisorModal.vue'
import NewSupervisorModal from '../Booking/ChangeSupervisor/NewSupervisorModal.vue'
import ChangeAddressModal from '../Booking/ChangeAddress/ChangeAddressModal.vue'

export default 
{    computed: 
        {  ...mapGetters({    }),
           ...mapState({  
               user: state => state.authUser,
                            selectedOrder: state => state.tab.selectedOrder,
                           // details: state => state.orderDetails.details,
                 }),
           order() {    if (this.selectedOrder)   return this.selectedOrder; else return null;  },


        },
    props: 
    {   user: {  type: Object,  default: null,  },
        selectedOrder: {  type: Object,  default: null,  },
        details: {  type: Object, default: null,  },
    },
    components: 
    {    'change-supervisor-modal': ChangeSupervisorModal,
        'new-supervisor-modal': NewSupervisorModal,
        'change-address-modal': ChangeAddressModal,
    },
    data () 
    {  return {
            isShowChangeSupervisor: false,
            isShowNewSupervisor: false,
            isShowChangeAddress: false,
        }
    },
    methods: 
    {  onClickChangeAddress() {   console.log('onClickChangeAddress');  this.onOpenChangeChangeAddressModal(); },
        onOpenChangeChangeAddressModal() {   console.log('onOpenChangeChangeAddressModal');  this.isShowChangeAddress = true; },
        onCloseChangeAddressModal(value) 
        {   console.log('onCloseChangeAddressModal value=', value);
            this.isShowChangeAddress = false;
            if (value) {     this.refreshOrderDetail();   }
        },
        selectSupervisor(dataItem)
        {   console.log('OrderInfo -> selectSupervisor. dataItem=', dataItem);
            // change the supvisor
            let formData = 
            {   quoteId: this.order? this.order.QUOTE_ID: '',
                quoteVers: this.order? this.order.QUOTE_VERS: '',
                contactId: dataItem.CONTACT_ID,
            };
            console.log('OrderInfo -> selectSupervisor formData=', formData);
            // send update request
            this.$store.dispatch('changeSupervisorRequest', formData)
                .then((response) => 
                {    console.log('changeSupervisorRequest response=', response);
                    // refresh the booking page
                    this.onCloseChangeSupervisorModal();
                    // update supervisor information on screen
                    // todo
                    this.refreshOrderDetail();
                })
                .catch((error) => { console.log('changeSupervisorRequest error=', error); });
        },
        newSupervisor() {  console.log('OrderInfo -> newSupervisor.'); this.onOpenNewSupervisorModal();   },
        onClickChangeSupervisor() { console.log('onClickChangeSupervisor.');  this.onOpenChangeSupervisorModal(); },
        onOpenChangeSupervisorModal() { console.log('onOpenChangeSupervisorModal'); this.isShowChangeSupervisor = true;   },
        onCloseChangeSupervisorModal() { console.log('onCloseChangeSupervisorModal'); this.isShowChangeSupervisor = false;   },
        onOpenNewSupervisorModal() { console.log('onOpenNewSupervisorModal'); this.isShowNewSupervisor = true; },
        onCloseNewSupervisorModal(value) 
            { console.log('onCloseNewSupervisorModal value=', value);
                 this.isShowNewSupervisor = false;
                 if (value == 'newSuccess')
                    {
                     // close supervisor list modal as well
                      this.onCloseChangeSupervisorModal();
                      // update supervisor information on screen
                      // todo
                     this.refreshOrderDetail();
                    }
            },
        refreshOrderDetail() 
        {   let payload = 
            {   orderNo: this.selectedOrder.UDF1,
                quoteId: this.selectedOrder.QUOTE_ID,
                location: this.selectedOrder.QUOTE_NUM_PREF,
            };
            this.$emit('refreshOrderDetail', payload);
        },
    }
}
</script>

<style scoped>
</style>
