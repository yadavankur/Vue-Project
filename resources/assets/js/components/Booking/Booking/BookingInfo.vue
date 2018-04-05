<template>
    <div id="root-booking-info">
        <div class="app-row" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#bookinginfo">
                        Booking Information
                    </a>
                </div>
                <div id="bookinginfo" class="panel-collapse collapse in table-responsive">
                    <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <td>Credit
                            </td>
                            <td class="center">
                                <div v-if="order && order.customer && order.customer.dbp_cust && order.customer.dbp_cust.CSHLDF != 'N'">
                                    <span class="label label-danger">ACCOUNT HOLDING</span>
                                    <span>Please call supervisor for further information!</span>
                                </div>
                                <div v-else> <span class="label label-info">OK TO BOOK</span> </div>
                            </td>
                        </tr>
                        <tr><td> Download Date </td><td>{{ order? order.UDF9 : '' }} </td> </tr>
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

    export default 
    {   computed: 
        {   ...mapGetters({    }),
            ...mapState({user: state => state.authUser, selectedOrder: state => state.tab.selectedOrder,  }),
            order() {   if (this.selectedOrder)  return this.selectedOrder;  else return null;  }, },
            created() {console.log('/components/booking/booking/BookingInfo.vue-BookingInfo vue created! creditInfo');  },
            data () {  return {}  },
            components: {   },
    }
</script>

<style scoped>
   
</style>