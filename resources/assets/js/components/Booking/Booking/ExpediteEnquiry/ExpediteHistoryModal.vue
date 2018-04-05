<template>
    <div>
        <div>
            <expedite-history-modal :value="isShowModal" @cancel="onClickOk" effect="fade" :backdrop="true" :large="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{expediteType == 'EXPEDITE_FEEDBACK' ? 'Expedite Date Feedback History' : 'Expedite Date Enquiry History'}} </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <expedite-history-body v-if="isShowModal"
                                           :orderId="orderId"
                                           :quoteId="quoteId"
                                           :location="location"
                                           :expediteType="expediteType"></expedite-history-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickOk">Close</button>
                </div>
            </expedite-history-modal>
        </div>
    </div>
</template>

<script>
    import modal from 'vue-strap/src/Modal'
    import ExpediteHistoryBody from './ExpediteHistoryBody.vue'
    export default {
        props: {
            isShowExpediteEnquiryHistory: false,
            expediteType: '',
            orderId: '',
            quoteId: '',
            location:  '',
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowExpediteEnquiryHistory;
                return this.isVisible;
            }
        },
        created() {
            console.log('ExpediteHistoryModal Component created.')
        },
        components: {
            'expedite-history-body': ExpediteHistoryBody,
            'expedite-history-modal': modal,
        },
        mounted() {
            console.log('ExpediteHistoryModal Component mounted.')
        },
        methods:
        {
            onClickOk() {
                console.log('onClickOk');
                this.$emit('onCloseExpediteEnquiryHistoryModal');
            },
        }
    }
</script>

<style scoped>
    .modal-body {
        margin-bottom: 15px;
    }
</style>


