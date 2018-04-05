<template>
    <div id="root-booking-list">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <order-info
                                v-if="orderinfo.permission !== 'D' && orderinfo.permission !== 'H'"
                                :user="user"
                                :selectedOrder="selectedOrder"
                                :key="selectedOrder"
                                :details="details"
                                @refreshOrderDetail="refreshOrderDetail"
                                @onSearch="onSearch"
                        ></order-info>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder">
                        <booking-info v-if="bookinginfo.permission !== 'D' && bookinginfo.permission !== 'H'"
                                      :key="selectedOrder"
                        >
                        </booking-info>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder && selectedOrder.customer && selectedOrder.customer.dbp_cust && selectedOrder.customer.dbp_cust.CSHLDF != 'Y'">
                        <booking-main v-if="bookingconfirmation.permission !== 'D' && bookingconfirmation.permission !== 'H'"
                                      :key="selectedOrder"
                                      @onSearch="onSearch"
                                      :orderId="selectedOrder?selectedOrder.UDF1:''">
                        </booking-main>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder">
                        <email-booking-info v-if="emailconfirmation.permission !== 'D' && emailconfirmation.permission !== 'H'"
                                            :key="selectedOrder"
                                            :orderId="selectedOrder?selectedOrder.UDF1: ''">
                        </email-booking-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import BookingInfo from './BookingInfo.vue'
    import BookingMain from './BookingMain.vue'
    import OrderInfo from './OrderInfo.vue'
    import OrderAvailabilitySum from './OrderAvailabilitySum.vue'
    import EmailBookingInfo from './EmailBookingInfo.vue'

    export default 
    {   computed: 
        {  ...mapGetters({    }),
           ...mapState
            ({  user: state => state.authUser,
                selectedOrder: state => state.tab.selectedOrder,
                details: state => state.orderDetails.details,

                OrderInfo: state => state.booking.components.OrderInfo,
                BookingInfo: state => state.booking.components.BookingInfo,
                EmailConfirmation: state => state.booking.components.EmailConfirmation,
                BookingConfirmation: state => state.booking.components.BookingConfirmation,
                OrderAvailabilitySum: state => state.booking.components.OrderAvailabilitySum,
            }),
            orderinfo() 
            {   console.log('/components/booking/booking/Booking.vue-orderinfo=', this.OrderInfo);
                return this.parseJsObject(this.OrderInfo);
            },
            bookinginfo() 
            {   console.log('/components/booking/booking/Booking.vue-bookinginfo=', this.BookingInfo);
                return this.parseJsObject(this.BookingInfo);
            },
            emailconfirmation() 
            {   console.log('/components/booking/booking/Booking.vue-emailconfirmation=', this.EmailConfirmation);
                return this.parseJsObject(this.EmailConfirmation);
            },
            bookingconfirmation() 
            {   console.log('/components/booking/booking/Booking.vue-bookingconfirmation=', this.BookingConfirmation);
                return this.parseJsObject(this.BookingConfirmation);
            },
            orderavailabilitysum() 
            {   console.log('/components/booking/booking/Booking.vue-orderavailabilitysum=', this.OrderAvailabilitySum);
                return this.parseJsObject(this.OrderAvailabilitySum);
            },
        },
        data () {   return {    }   },
        beforeDestroy() 
        {   console.log('/components/booking/booking/Booking.vue-Booking vue beforeDestroy!');
            this.$store.dispatch('unsetOrderDetails');
        },
        created() 
        {   console.log('/components/booking/booking/Booking.vue-Booking vue created!');
            this.$store.dispatch('setBookingComponents')
                .then((response) => 
                {   console.log('/components/booking/booking/Booking.vue-setBookingComponents response=', response);
                    if (this.orderinfo.permission !== 'D' && this.orderinfo.permission !== 'H')
                    {   if (!this.selectedOrder || !this.selectedOrder.UDF1) return;
                        let payload = 
                        {   orderNo: this.selectedOrder.UDF1,
                            quoteId: this.selectedOrder.QUOTE_ID,
                            location: this.selectedOrder.QUOTE_NUM_PREF,
                        };
                        this.$store.dispatch('getOrderDetail', payload)
                            .then((response) => 
                            { console.log('/components/booking/booking/Booking.vue-Booking vue created getOrderDetail response=', response);
                            })
                            .catch((error) => {   })
                    }
                })
                .catch((error) => 
                {  console.log('/components/booking/booking/Booking.vue-Booking vue created error=', error);
                });

        },
        mounted() {console.log('/components/booking/booking/Booking.vue-Booking vue mounted'); },
        components: 
        {   'booking-info': BookingInfo,
            'booking-main': BookingMain,
            'order-info': OrderInfo,
            'order-availability-sum': OrderAvailabilitySum,
            'email-booking-info': EmailBookingInfo,
        },
        methods: 
        {   refreshOrderDetail(payload) 
            {   console.log('/components/booking/booking/Booking.vue-Booking refreshOrderDetail payload=', payload);
                this.onSearch(payload);
            },
            onSearch(payload) 
            {   console.log('/components/booking/booking/Booking.vue-onSearch payload=', payload);
                this.$store.dispatch('getOrderDetailOnSearch', payload)
                    .then((response) => 
                    {   console.log('/components/booking/booking/Booking.vue- getOrderDetailOnSearch response=', response);
                        if (this.isEmpty(response.body))
                        { this.$store.dispatch('setSelectedOrder', null);  }
                        else
                        {   this.$store.dispatch('setSelectedOrder', response.body);
                    //goes to tab.js--setSelectedOrder:(type: types.SET_TAB_SELECTED_ORDER, selectedOrder: data  });
                            let payload = 
                            {   orderNo: this.selectedOrder.UDF1,
                                quoteId: this.selectedOrder.QUOTE_ID,
                                location: this.selectedOrder.QUOTE_NUM_PREF,
                            };
                            this.$store.dispatch('getOrderDetail', payload)
                            //goes to---order-details.js-order-details getOrderDetail
                        }
                    })
                    .catch((error) => 
                    {   console.log('/components/booking/booking/Booking.vue-Booking vue created getOrderDetailOnSearch error=', error);
                        this.$store.dispatch('setSelectedOrder', null);
                    })
            }
        }
    }
</script>

<style scoped>
</style>
