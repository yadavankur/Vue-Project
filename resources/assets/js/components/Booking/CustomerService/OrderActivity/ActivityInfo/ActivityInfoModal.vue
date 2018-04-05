<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{ actionTitle }}  </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <cs-activity-info-body v-if="isShowModal"
                                           ref="csactivityInfoBody"
                                           :itemData="itemData"
                                           :actionType="actionType"
                                           :order="order"
                                           @onOpenCPMActivityNotesHistoryModal="onOpenCPMActivityNotesHistoryModal"
                    ></cs-activity-info-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickCancel">Cancel</button>
                    <button v-if="actionType == 'update'" type="button" class="btn btn-primary" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import CSActivityInfoBody from './ActivityInfoBody.vue'

    export default {
        props: {
            isShowActivityInfo: false,
            actionType: '',
            actionTitle: '',
            order: {
                type: Object,
            },
            itemData: {
                type: Object,
            }
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowActivityInfo;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-ActivityInfoModal Component created.')
        },
        components: {
            'cs-activity-info-body': CSActivityInfoBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-ActivityInfoModal Component mounted.')
        },
        methods:
        {
            onOpenCPMActivityNotesHistoryModal(dataItem) {
                this.$emit('onOpenCPMActivityNotesHistoryModal', dataItem);
            },
            onClickCancel() {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-onClickCancel');
                this.$emit('onCloseActivityInfoModal');
            },
            onClickSave() {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-onClickSave');
                if (this.isEmpty(this.$refs.csactivityInfoBody.newDeliveryDate))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the new delivery date!');
                    return;
                }
                if (this.$refs.csactivityInfoBody.status == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the status!');
                    return;
                }
                // check validation
                if (this.isEmpty(this.$refs.csactivityInfoBody.dowellNotes))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the reason that you update this activity!');
                    return;
                }
                this.$emit('onCloseActivityInfoModal');
                // send save request
                let formData = {
                    quoteId: this.itemData.quote_id,
                    locationId: this.itemData.location_id,
                    orderId: this.itemData.order_id,
                    activityId: this.itemData.activity_id,
                    notes: this.$refs.csactivityInfoBody.dowellNotes,
                    deliveryDate: this.$refs.csactivityInfoBody.newDeliveryDate,
                    orderMatrixId: this.itemData.id,
                    status: this.$refs.csactivityInfoBody.status,
                    //type: 'DOWELL_NOTES',
                };
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-onClickSave formData=', formData);
                // SEND EMAIL REQUEST
                this.$store.dispatch('saveActivityDowellNotesRequest', formData)
                    .then((response) => {
                        console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-saveActivityDowellNotesRequest response=', response);
                        // fire refresh event
                        this.$emit('onRefreshOrderMatrixTable');
                    })
                    .catch((error) => {
                        console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityInfoModal.vue-saveActivityDowellNotesRequest error=', error);
                    });

            }
        }
    }
</script>

<style scoped>
</style>


