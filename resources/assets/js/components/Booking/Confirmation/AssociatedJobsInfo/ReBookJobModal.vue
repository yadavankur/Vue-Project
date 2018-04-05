<template>
    <div>
        <expedite-enquiry-modal
                :isShowExpediteEnquiry="isShowExpediteEnquiry"
                :expediteType="expediteType"
                :order="selectedOrder? selectedOrder.order : null"
                @onCloseExpediteEnquiryModal="onCloseExpediteEnquiryModal"
        >
        </expedite-enquiry-modal>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Re-Booking Confirmation </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <rebook-job-body v-if="isShowModal"
                                     ref="reBookJob"
                                     @onClickRequestOrderExpedite="onClickRequestOrderExpedite"
                                     @onCloseReBookJobModal="onCloseReBookJobModal"
                                     @onChangeDeliveryDate="onChangeDeliveryDate">
                    </rebook-job-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickCancel">Close</button>
                    <button type="button" class="btn btn-primary" @click="onClickSaveNotes">Save Notes</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import ReBookJobBody from './ReBookJobBody.vue'
    import ExpediteEnquiryModal from '../../Booking/ExpediteEnquiry/ExpediteEnquiryModal.vue'

    export default {
        props: {
            isShowRebookJob: false,
        },
        data () {
            return {
                isVisible: false,
                canBookable: false,
                expediteType: 'EXPEDITE',
                isShowExpediteEnquiry: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowRebookJob;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        created() {
            console.log('ReBookJobModal Component created.')
        },
        components: {
            'rebook-job-body': ReBookJobBody,
            'custom-modal': modal,
            'expedite-enquiry-modal': ExpediteEnquiryModal,
        },
        mounted() {
            console.log('ReBookJobModal Component mounted.')
        },
        methods:
        {
            onClickRequestOrderExpedite() {
                console.log('onClickRequestOrderExpedite');
                this.isShowExpediteEnquiry = true;
            },
            onCloseExpediteEnquiryModal() {
                console.log('onCloseExpediteEnquiryModal');
                this.isShowExpediteEnquiry = false;
            },
            onClickSaveNotes() {
                console.log('onClickSaveNotes');
                this.$emit('onCloseRebookJobModal');
                // send save request
                let formData = {
                    orderId: this.selectedOrder? this.selectedOrder.order_id : '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    notes: this.$refs.reBookJob.reBookingNotesContent
                };
                console.log('onClickSave formData=', formData);
                // SEND REQUEST
                this.$store.dispatch('saveDowellNotesRequest', formData)
                    .then((response) => {
                        console.log('saveDowellNotesRequest response=', response);
                        this.$events.fire('refreshLastDowellNotes');
                        })
                    .catch((error) => {
                            console.log('saveDowellNotesRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseRebookJobModal');
            },
            onChangeDeliveryDate(canBookable) {
                console.log('onChangeDeliveryDate = canBookable', canBookable);
                this.canBookable = canBookable;
            },
            onCloseReBookJobModal() {
                console.log('onCloseReBookJobModal');
                this.$emit('onCloseRebookJobModal');
            }
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
</style>


