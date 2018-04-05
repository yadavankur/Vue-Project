<template>
    <div id="root-order-info">
        <div class="app-row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#orderinfo">
                        Order Information
                    </a>
                </div>
                <div id="orderinfo" class="panel-collapse collapse in table-responsive">
                    <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                        <tbody>
                        <tr> <td>Sales Order Number </td><td colspan="2"> {{order? order.UDF1 : ''}} </td>  </tr>
                        <tr> <td>Sales Quote Number</td> <td colspan="2"> {{order? order.QUOTE_NUM : ''}}</td> </tr>
                        <tr>
                            <td>Customer </td>
                            <td>{{ order && order.customer ? order.customer.CUST_NAME : '' }} </td>
                            <td>{{ order && order.customer ? order.customer.CUST_CODE : '' }}</td>
                        </tr>
                        <tr>
                            <td>Customer Email Address </td>
                            <td colspan="2"> {{ order && order.customer ? order.customer.EMAIL_ADDR : '' }} </td>
                        </tr>
                        <tr> <td>Customer Tier </td>
                            <td colspan="2">{{ order && order.tier? (order.tier.name) : '' }} </td>
                        </tr>
                        <tr>
                            <td>Dowell Sales Contact
                            </td>
                            <td colspan="2">{{ order && order.sales_person ? order.sales_person.USER_NAME : ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td >Order Details
                            </td>
                            <td colspan="2">{{ order && order.deliver_address?
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
                        <tr> <td>Colour </td> <td colspan="2">{{ color? color.DESCR : ''}}  </td></tr>
                        <tr> <td>Supervisor Updated </td>
                            <td colspan="2">{{ order && order.contact ? order.contact.CONTACT_NAME + ' '
                                + (order.contact.MOBILE?order.contact.MOBILE:'') : ''}}
                            </td>
                        </tr>
                        <tr>
                            <td>Supervisor Contact Email</td>
                            <td colspan="2">{{ order && order.contact ? order.contact.EMAIL_ADDR : ''}} </td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td colspan="2">{{ order && order.status? order.status.DESCR : ''}} </td>
                        </tr>
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

    export default 
    {   methods: { },
        computed: {...mapState({ details: state => state.orderDetails.details,  }),
                   order() { if (this.selectedOrder) return this.selectedOrder;
                             else return null;
                           },
                   color() { if (this.details && this.details.color)  return this.details.color;
                             else return null;
                           }
                  },
        components: {   'bs-input': input,  },
        props:  {  user: {   type: Object, default: null,  },
                  selectedOrder: { type: Object, default: null,  },
                },
        data () {  return {  orderNo: '',  }   },
        created() { if (this.order)   this.orderNo = this.order.UDF1; //get order details
                    if (!this.selectedOrder || !this.selectedOrder.UDF1) return;
                    let payload = {  orderNo: this.selectedOrder.UDF1,
                                     quoteId: this.selectedOrder.QUOTE_ID,
                                     location: this.selectedOrder.QUOTE_NUM_PREF,
                                 };
                    this.$store.dispatch('getOrderDetail', payload)
                    .then((response) => { console.log('Booking vue created getOrderDetail response=', response);  })
                    .catch((error) => {            })
                  }
    }
</script>
<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/
    .form-group {   margin-bottom: 0px !important;  }
    .input-group {  height: 0px !important; margin-bottom: 0px !important;   }
</style>
