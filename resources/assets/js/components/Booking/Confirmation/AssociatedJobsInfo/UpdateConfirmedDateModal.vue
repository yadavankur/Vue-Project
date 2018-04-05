<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Updating Agreed Delivery Date </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <update-confirmed-date-body v-if="isShowModal"
                        ref="updateConfirmedDateBody"
                        :itemData="itemData"
                    ></update-confirmed-date-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import UpdateConfirmedDateBody from './UpdateConfirmedDateBody.vue'

    export default {
        props: {
            isShowUpdateConfirmedDate: false,
            itemData: null,
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowUpdateConfirmedDate;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        created() {
            console.log('UpdateConfirmedDateModal Component created.')
        },
        components: {
            'update-confirmed-date-body': UpdateConfirmedDateBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('UpdateConfirmedDateModal Component mounted.')
        },
        methods:
            {
                onClickSave() {
                    console.log('onClickSave');
                    if (this.isEmpty(this.$refs.updateConfirmedDateBody.formData.newAgreedDate))
                    {
                        this.$store.dispatch('showErrorNotification', 'Please input the agreed delivery date!');
                        return;
                    }
                    this.$emit('onCloseUpdateConfirmedDateModal');
                    // call api to save confirmed date
                    // send save request
                    let formData = this.$refs.updateConfirmedDateBody.formData;
                    console.log('onClickSave formData=', formData);
                    // send update request
                    this.$store.dispatch('updateAssociatedJobRequest', formData)
                        .then((response) => {
                            console.log('updateAssociatedJobRequest response=', response);
                            // refresh the customer notes
                            this.$events.fire('refreshConfirmationAssociatedJobsListTable');
                            this.$events.fire('refreshBookingListTable');
                            this.$events.fire('refreshLastDowellNotes');
                        })
                        .catch((error) => {
                            console.log('updateAssociatedJobRequest error=', error);
                        });
                },
                onClickCancel() {
                    console.log('onClickCancel');
                    this.$emit('onCloseUpdateConfirmedDateModal');
                }
            }
    }
</script>

<style scoped>
</style>


