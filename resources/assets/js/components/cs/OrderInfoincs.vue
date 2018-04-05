<template>
  <div id="root-order-info">
    <div class="app-row">



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
                <tr><td>Sales Order Number</td><td colspan="2"> {{order? order.UDF1 : ''}}</td></tr>
                <tr> <td>Delivery Date</td>
                    <td colspan="2"> {{order? formatDate(order.DELIVERY_DATE, 'DD/MMM/YYYY') : ''}} </td>
                </tr>
               
                 
                  <tr><td>Customer: NAME&CODE</td>
                    <td>{{ order  ? order.CUST_NAME : '' }}</td>
                    <td>{{ order ? order.CUST_CODE : '' }}</td>
                  </tr>
                  <tr><td>Location</td><td colspan="2"> {{order? order.QUOTE_NUM_PREF : ''}}</td></tr>
                  <tr>
                    <td>Dowell Sales Contact: NAME&CODE</td>
                    <td>{{ order ? order.USER_NAME : ''  }}</td>
                    <td>{{ order ? order.USER_CODE : '' }}</td>
                  </tr>                
                  <tr>
                    <td>Supervisor From V6</td>
                    <td colspan="2">{{ order ? order.CONTACT_NAME + ' ' + (order.MOBILE?order.MOBILE:order.PHONE) : ''}}
                    </td>
                  </tr>
                  <tr><td >Delivery Address</td>
                    <td colspan="2">
                        {{ order ?
                                          (order.ADDR_1? order.ADDR_1 : '')
                                        + (order.ADDR_2? ' ' + order.ADDR_2: '')
                                        + (order.ADDR_3? ' ' + order.ADDR_3: '')
                                        + (order.ADDR_4? ' ' + order.ADDR_4: '')
                                        + (order.ADDR_5? ' ' + order.ADDR_5: '')
                                        + (order.ADDR_6? ' ' + order.ADDR_6: '')
                                        : ''
                        }}
                    </td>
                  </tr>
                 <tr><td>Status </td><td colspan="2">{{ order ? order.DESCR : ''}} </td> </tr>
                  <tr><td>Total Amount </td><td colspan="2"> {{ order? formatPrice(order.TOTALAMOUNT) : '' }} </td>
                  </tr>
                  <tr><td>Quote Number</td><td colspan="2">{{ order? (order.QUOTE_NUM_PREF + order.QUOTE_NUM + '_' + order.QUOTE_NUM_SUFF + '_' + order.QUOTE_VERS) : '' }}</td> </tr>
                  <tr><td>Entered By</td> <td colspan="2">{{ order? (order.UDF7) : '' }}</td></tr>
                  <tr><td>Entered Date</td><td colspan="2">{{ order? formatDate(order.QUOTE_DATE, 'DD/MMM/YYYY') : '' }}</td></tr>
                  <tr><td>Downloaded By</td><td colspan="2">{{ order? order.UDF8 : '' }}</td></tr>
                  <tr><td>Downloaded Date</td><td colspan="2">{{ order? order.UDF9 : '' }}</td></tr>
                  <tr><td>Customer Tier</td><td colspan="2"> {{ order && order.tier? (order.tier.name) : '' }} </td> </tr>

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
//import ChangeSupervisorModal from '../Booking/ChangeSupervisor/ChangeSupervisorModal.vue'
//import NewSupervisorModal from '../Booking/ChangeSupervisor/NewSupervisorModal.vue'
//import ChangeAddressModal from '../Booking/ChangeAddress/ChangeAddressModal.vue'

export default 
{    computed: 
        {  ...mapGetters({    }),
           ...mapState({  
               user: state => state.authUser,
                            selectedOrder: state => state.cstkt.selectedTicket.v6quotee,
                            selectedTicket: state => state.cstkt.selectedTicket,
                           // details: state => state.orderDetails.details,
                            
                 }),
           selectedTicket1() { return this.selectedTicket; },
           order() {    if (this.selectedOrder)  
                        { 
                            console.log('orderinfoincs-',this.selectedOrder); 
                            var a=parseFloat(this.selectedOrder.NETT_SELL_PRICE);
                            var b=parseFloat(this.selectedOrder.NETT_SELL_PRC_LAB);
                            var c= a+b;
                         //   console.log('a-',a); 
                          //  console.log('b-',b); 
                          //  console.log('c-',c); 
                            this.selectedOrder.TOTALAMOUNT=c;
                            return this.selectedOrder;
                         } else return null; 
           
               },


        },
         created() {  console.log('/cs/orderinfoincs -vue Component created.'); 
                     console.log('orderinfoincs-created',this.selectedOrder); 
                    },
         modified() {  console.log('/cs/orderinfoincs -vue Component modified.'); 
                     console.log('orderinfoincs-modified',this.selectedOrder); 
                    },
    props: 
    {   user: {  type: Object,  default: null,  },
        selectedOrder: {  type: Object,  default: null,  },
        details: {  type: Object, default: null,  },
    },
    components: 
    {   // 'change-supervisor-modal': ChangeSupervisorModal,
       // 'new-supervisor-modal': NewSupervisorModal,
       // 'change-address-modal': ChangeAddressModal,
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
