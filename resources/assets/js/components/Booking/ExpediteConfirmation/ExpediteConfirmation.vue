<template>
    <div id="root-expedite-confirmation">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ec-booking-list-search></ec-booking-list-search>
                    </div>
                    <div class="col-lg-12">
                        <ec-booking-list-result
                                @onSelectRow="onSelectRow"
                        ></ec-booking-list-result>
                    </div>
                    <div class="col-lg-12" v-if="selectedOrder">
                        <expedite-enquiry-list
                                :key="selectedOrder.order_id"
                                :selectedOrder="selectedOrder"></expedite-enquiry-list>
                    </div>
                    <div class="col-lg-12" v-if="selectedOrder">
                        <order-activity-list
                                :key="selectedOrder.order_id"
                                :selectedOrder="selectedOrder"></order-activity-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import ECBookingListSearch from './BookingList/BookingListSearchSection.vue'
    import ECBookingListResult from './BookingList/BookingListResultSection.vue'

    import ExpediteEnquiryList from './ExpediteEnquiryList.vue'
    import OrderActivityList from './OrderActivityList.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                selectedOrder: null,
            }
        },
        created() {
            console.log('ExpediteConfirmation vue created!');
        },
        mounted() {
            console.log('ExpediteConfirmation vue mounted');
        },
        components: {
            'expedite-enquiry-list': ExpediteEnquiryList,
            'order-activity-list': OrderActivityList,
            'ec-booking-list-search': ECBookingListSearch,
            'ec-booking-list-result': ECBookingListResult,
        },
        methods: {
            onSelectRow(rowItem)
            {
                console.log('ExpediteConfirmation -> onSelectRow rowItem=', rowItem);
                this.selectedOrder = rowItem;
            }
        }
    }
</script>

<style scoped>
</style>
