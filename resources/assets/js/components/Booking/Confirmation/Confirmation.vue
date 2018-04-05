<template>
    <div id="root-confirmation-list">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <booking-list
                                v-if="bookinglistinfo.permission !== 'D' && bookinglistinfo.permission !== 'H'"
                        ></booking-list>
                    </div>
                    <div class="col-md-12"  v-if="selectedOrder">
                        <confirmation-order-info
                                :user="user"
                                :selectedOrder="selectedOrder.order"
                                :details="details"
                                @refreshOrderDetail="refreshOrderDetail"
                                :key ="selectedOrder"></confirmation-order-info>
                    </div>
                    <div class="col-md-12"  v-if="selectedOrder">
                        <confirmation-credit-info :order="selectedOrder"></confirmation-credit-info>
                    </div>
                    <div class="col-md-12"  v-if="selectedOrder">
                        <associated-jobs-info v-if="associatedjobsinfo.permission !== 'D' && associatedjobsinfo.permission !== 'H'"
                                              :key ="selectedOrder"
                        >
                        </associated-jobs-info>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder" id='associated-task-list'>
                        <associated-tasks-list :key="selectedOrder"
                                               :selectedOrder="selectedOrder"
                        ></associated-tasks-list>
                    </div>
                    <div class="col-md-12" v-if="selectedOrder">
                        <comments-info v-if="commentsinfo.permission !== 'D' && commentsinfo.permission !== 'H'"
                                       :title="title"
                                       :quoteId="selectedOrder? selectedOrder.quote_id: ''"
                                       :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                                       :orderId="selectedOrder? selectedOrder.order_id: ''"
                                       :key ="selectedOrder"
                         >
                        </comments-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import BookingList from './BookingList.vue'
    import AssociatedJobsInfo from './AssociatedJobsInfo.vue'
    import CommentsInfo from './CommentsInfo.vue'
    import ConfirmationOrderInfo from '../OrderDetails/OrderInfo.vue'
    import ConfirmationCreditInfo from './ConfirmationCreditInfo.vue'
    import AssociatedTasks from '../Booking/AssociatedTasks/AssociatedTasks.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.confirmation.selectedOrder,
                details:  state => state.confirmation.details,
                BookingListInfo: state => state.confirmation.components.BookingListInfo,
                AssociatedJobsInfo: state => state.confirmation.components.AssociatedJobsInfo,
                CommentsInfo: state => state.confirmation.components.CommentsInfo,
            }),
            bookinglistinfo() {
                console.log('bookinglistinfo=', this.BookingListInfo);
                return this.parseJsObject(this.BookingListInfo);
            },
            associatedjobsinfo() {
                console.log('associatedjobsinfo=', this.AssociatedJobsInfo);
                return this.parseJsObject(this.AssociatedJobsInfo);
            },
            commentsinfo() {
                console.log('commentsinfo=', this.CommentsInfo);
                return this.parseJsObject(this.CommentsInfo);
            },
            confirmationoption() {
                console.log('confirmationoption=', this.ConfirmationOption);
                return this.parseJsObject(this.ConfirmationOption);
            },
        },
        data () {
            return {
                title: "Confirmation Comments",
            }
        },
        created() {
            console.log('Confirmation vue created!');
            // if the order isn't selected
//            if (!this.selectedOrder)
//            {
//                this.$router.push({name: 'notallowed'});
//            }
            // clear selected order
            this.$store.dispatch('setConfirmationSelectedOrder', null);
            // get accessible components
            this.$store.dispatch('setConfirmationComponents')
                .then((response) => {
                    console.log('Confirmation vue created setConfirmationComponents response=', response);

                })
                .catch((error) => {
                    console.log('Confirmation vue created error=', error);
                });

        },
        mounted() {
            console.log('Confirmation vue mounted');
        },
        components: {
            'booking-list': BookingList,
            'associated-jobs-info': AssociatedJobsInfo,
            'comments-info': CommentsInfo,
            'confirmation-order-info': ConfirmationOrderInfo,
            'confirmation-credit-info': ConfirmationCreditInfo,
            'associated-tasks-list': AssociatedTasks,
        },
        methods: {
            refreshOrderDetail(dataItem) {
                console.log('Confirmation refreshOrderDetail dataItem=', dataItem);
                let payload = {
                    orderId: dataItem.orderNo,
                    quoteId: dataItem.quoteId,
                    location: dataItem.location,
                };
                this.$store.dispatch('refreshSelectedOrder', payload)
                    .then((response) => {
                        console.log('Confirmation refreshOrderDetail response=', response);
                    })
                    .catch((error) => {
                    });
            },
        },
    }
</script>

<style scoped>
</style>
