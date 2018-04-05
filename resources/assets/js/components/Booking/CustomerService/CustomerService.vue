<template>
    <div id="root-customer-service-list">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <cs-booking-list v-if="bookinglistinfo.permission !== 'D' && bookinglistinfo.permission !== 'H'"></cs-booking-list>
                    </div>
                    <div class="col-lg-6" v-if="selectedOrder">
                        <cs-order-info
                                v-if="orderinfo.permission !== 'D' && orderinfo.permission !== 'H'"
                                :key ="selectedOrder"
                                :user="user"
                                :selectedOrder="selectedOrder.order"
                        >
                        </cs-order-info>
                    </div>
                    <div class="col-lg-6"  v-if="selectedOrder">
                        <cs-credit-info :order="selectedOrder"></cs-credit-info>
                    </div>
                    <div class="col-md-12"  v-if="selectedOrder">
                        <cs-associated-jobs-info
                                v-if="associatedjobsinfo.permission !== 'D' && associatedjobsinfo.permission !== 'H'"
                                :key ="selectedOrder"
                        >
                        </cs-associated-jobs-info>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder">
                        <cs-comments-info v-if="commentsinfo.permission !== 'D' && commentsinfo.permission !== 'H'"
                                          :title="title"
                                          :orderId="selectedOrder? selectedOrder.order_id: ''"
                                          :quoteId="selectedOrder? selectedOrder.quote_id: ''"
                                          :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                                          :key ="selectedOrder"
                        >
                        </cs-comments-info>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder">
                        <cs-order-activity-summary
                                v-if="orderactivitysummary.permission !== 'D' && orderactivitysummary.permission !== 'H'"
                                :orderId="selectedOrder? selectedOrder.order_id: ''"
                                :key ="selectedOrder"
                        >
                        </cs-order-activity-summary>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import CSOrderInfo from './OrderInfo.vue'
    import CSBookingList from './BookingList.vue'
    import CSAssociatedJobsInfo from './AssociatedJobsInfo.vue'
    import CSOrderActivitySummary from './OrderActivitySummary.vue'
    import CSCommentsInfo from '../Confirmation/CommentsInfo.vue'
    import CSCreditInfo from '../Confirmation/ConfirmationCreditInfo.vue'

    export default 
    {   computed: 
           {    ...mapGetters({    }),           
                ...mapState(
                   {   user: state => state.authUser, selectedOrder: state => state.customerService.selectedOrder,
                       details: state => state.orderDetails.details, BookingListInfo: state => state.customerService.components.BookingListInfo,
                       AssociatedJobsInfo: state => state.customerService.components.AssociatedJobsInfo,
                       OrderInfo: state => state.customerService.components.OrderInfo,
                       CommentsInfo: state => state.customerService.components.CommentsInfo,
                       OrderActivitySummary: state => state.customerService.components.OrderActivitySummary,
                    }),
                bookinglistinfo() {  console.log('/components/booking/customerservice/customerservice.vue-bookinglistinfo=', this.BookingListInfo);
                                    return this.parseJsObject(this.BookingListInfo);
                                 },
                associatedjobsinfo() { console.log('/components/booking/customerservice/customerservice.vue-associatedjobsinfo=', this.AssociatedJobsInfo);
                                      return this.parseJsObject(this.AssociatedJobsInfo);
                                   },
                commentsinfo() {  console.log('/components/booking/customerservice/customerservice.vue-commentsinfo=', this.CommentsInfo);
                                 return this.parseJsObject(this.CommentsInfo);
                               },
                orderinfo() {   console.log('/components/booking/customerservice/customerservice.vue-orderinfo=', this.OrderInfo);
                                return this.parseJsObject(this.OrderInfo);
                            },
                orderactivitysummary(){  console.log('/components/booking/customerservice/customerservice.vue-orderactivitysummary=', this.OrderActivitySummary);
                                        return this.parseJsObject(this.OrderActivitySummary);
                                     },
            },
        data () {  return {   title: "Interaction Notes",    }    },
        created() { console.log('/components/booking/customerservice/customerservice.vue-CustomerService vue created!');
                    // if the order isn't selected
                    // if (!this.selectedOrder) { this.$router.push({name: 'notallowed'});   }   // clear selected order
                     this.$store.dispatch('setCustomerServiceSelectedOrder', null);
                    // get accessible components
                    this.$store.dispatch('setCustomerServiceComponents')
                    .then((response) => { console.log('/components/booking/customerservice/customerservice.vue-CustomerService vue created setCustomerServiceComponents response=', response); })
                    .catch((error) => { console.log('/components/booking/customerservice/customerservice.vue-CustomerService vue created error=', error);   });
                  },
        mounted() {  console.log('/components/booking/customerservice/customerservice.vue-CustomerService vue mounted');    },
        components: { 'cs-booking-list': CSBookingList,'cs-associated-jobs-info': CSAssociatedJobsInfo,
                      'cs-order-info': CSOrderInfo, 'cs-order-activity-summary': CSOrderActivitySummary,
                      'cs-comments-info': CSCommentsInfo, 'cs-credit-info': CSCreditInfo,
                    },
        methods: {        },
    }
</script>

<style scoped>
</style>
