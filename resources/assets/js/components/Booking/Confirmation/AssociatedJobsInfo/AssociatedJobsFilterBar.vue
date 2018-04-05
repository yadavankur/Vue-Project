<template>
    <div class="filter-bar">
        <rebook-job-modal
                :isShowRebookJob="isShowRebookJob"
                @onCloseRebookJobModal="onCloseRebookJobModal">
        </rebook-job-modal>
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <bs-input placeholder="so no or description..." type="text" :rows="1" :maxlength="255" v-model="filterText"></bs-input>
                <button class="btn btn-success btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="resetFilter">Reset</button>
                <!--<button class="btn btn-danger btn-sm"-->
                        <!--:disabled="selectedOrder? false: true"-->
                        <!--@click.prevent="reBookJob">RE-BOOK JOB</button>-->
                <button class="btn btn-danger btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="confirmOrder">Confirm</button>
                <button class="btn btn-primary btn-sm"
                        :disabled="selectedOrder && selectedIds.length? false: true"
                        @click.prevent="printList">
                    <i class="glyphicon glyphicon-print"></i> Print List</button>
                <button class="btn btn-info btn-sm"
                        :disabled="selectedOrder && selectedIds.length? false: true"
                        @click.prevent="emailList">
                    <i class="glyphicon glyphicon-envelope"></i> Email List</button>
            </div>
        </form>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'
    import ReBookJobModal from './ReBookJobModal.vue'
    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        components: {
            'bs-input': input,
            'rebook-job-modal': ReBookJobModal,
        },
        props: {
            selectedIds: {
                type: Array,
            },
        },
        data () {
            return {
                isShowRebookJob: false,
                filterText: '',
            }
        },
        created() {
        },
        events: {
            'confirmation-associated-jobs-reset' () {
                console.log('confirmation-associated-jobs-reset');
                this.resetFilter();
            },
        },
        methods: {
            confirmOrder() {
                console.log('confirmOrder');
                this.$events.fire('confirmation-main-job-confirm', this.selectedOrder)
            },
            doFilter () {
                this.$events.fire('confirmation-associated-jobs-list-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = '';
                this.$events.fire('confirmation-associated-jobs-list-filter-reset')
            },
            reBookJob() {
                console.log('reBookJob');
                //this.isShowRebookJob = true;
                this.onClickGoToBookingScreen();
            },
            printList() {
                console.log('printList');
                this.$emit('onPrintJobsList');
            },
            emailList() {
                console.log('emailList');
                this.$emit('onEmailJobsList');
            },
            onCloseRebookJobModal() {
                console.log('onCloseRebookJobModal');
                this.isShowRebookJob = false;
            },
            onClickGoToBookingScreen() {
                console.log('onClickGoToBookingScreen');
                let orderNo = this.selectedOrder.order_id;
                let payload = {
                    orderNo: orderNo,
                    quoteId: this.selectedOrder.quote_id,
                    location: this.selectedOrder.QUOTE_NUM_PREF,
                };
                this.$store.dispatch('getOrderDetailOnSearch', payload)
                    .then((response) => {
                        console.log('onClickGoToBookingScreen getOrderDetailOnSearch response=', response);
                        if (this.isEmpty(response.body))
                        {
                            this.$store.dispatch('setSelectedOrder', null);
                            this.$store.dispatch('hideAllNotifications');
                            this.$router.push({name: 'orderlist'});
                        }
                        else
                        {
                            this.$store.dispatch('setSelectedOrder', response.body);
                            this.$store.dispatch('getOrderDetail', payload);
                            this.$store.dispatch('hideAllNotifications');
                            this.$router.push({name: 'booking'});
                        }
                    })
                    .catch((error) => {
                        console.log('onClickGoToBookingScreen getOrderDetailOnSearch error=', error);
                        this.$store.dispatch('setSelectedOrder', null);
                        this.$store.dispatch('hideAllNotifications');
                        this.$router.push({name: 'orderlist'});
                    });

            },
        }
    }
</script>
<style scoped>

</style>