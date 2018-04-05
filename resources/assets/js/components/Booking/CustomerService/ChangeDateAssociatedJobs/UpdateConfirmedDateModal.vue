<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Updating Confirmed Date </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <cd-update-confirmed-date-body v-if="isShowModal"
                        ref="cdUpdateConfirmedDateBody"
                        :itemData="itemData"
                    ></cd-update-confirmed-date-body>
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
    import CDUpdateConfirmedDateBody from './UpdateConfirmedDateBody.vue'

    export default {
        props: {
            isShowUpdateConfirmedDate: false,
            itemData: null,
            selectedOrder: {
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
                this.isVisible = this.isShowUpdateConfirmedDate;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
                //selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        created() {
            console.log('CDUpdateConfirmedDateModal Component created.')
        },
        components: {
            'cd-update-confirmed-date-body': CDUpdateConfirmedDateBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('CDUpdateConfirmedDateModal Component mounted.')
        },
        methods:
        {
            onClickSave() {
                console.log('onClickSave');
                this.$emit('onCloseUpdateConfirmedDateModal');
                // call api to save confirmed date
                // send save request
                let formData = this.$refs.cdUpdateConfirmedDateBody.formData;
                console.log('onClickSave formData=', formData);
                // send update request
                this.$store.dispatch('updateAssociatedJobRequest', formData)
                    .then((response) => {
                        console.log('updateAssociatedJobRequest response=', response);
                        // refresh the table
                        this.$events.fire('refreshCDConfirmationAssociatedJobsListTable');
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


