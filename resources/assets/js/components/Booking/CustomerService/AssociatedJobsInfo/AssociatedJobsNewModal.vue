<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Adding new associated jobs </h4>
                </div>
                <div slot="modal-body" class="modal-body table-responsive">
                    <cs-associated-jobs-all-list-table v-if="isShowModal"
                        ref="csassociatedJobsAll"
                    ></cs-associated-jobs-all-list-table>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickOk">OK</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import CSAssociatedJobsAllListTable from './AssociatedJobsAllListTable.vue'

    export default {
        props: {
            isShowNewModal: false,
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowNewModal;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.customerService.selectedOrder,
            }),
        },
        created() {
            console.log('CSAssociatedJobsNewModal Component created.')
        },
        components: {
            'cs-associated-jobs-all-list-table': CSAssociatedJobsAllListTable,
            'custom-modal': modal,
        },
        mounted() {
            console.log('CSAssociatedJobsNewModal Component mounted.')
        },
        methods:
            {
                onClickOk() {
                    console.log('onClickOk this.$refs.csassociatedJobsAll.csassociatedJobsAllVueTable=', this.$refs.csassociatedJobsAll.$refs.csassociatedJobsAllVueTable);

                    // get the selected jobs
                    let selectedJobIds = this.$refs.csassociatedJobsAll.$refs.csassociatedJobsAllVueTable.selectedTo;
                    console.log('onClickOk selectedJobIds.length=', selectedJobIds.length);
                    if (selectedJobIds.length > 0)
                    {
                        this.$emit('onCloseNewModal');
                        // add new asscociated jobs to
                        let data = {
                            orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                            quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                            location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                            quoteIds: selectedJobIds
                        };
                        this.$store.dispatch('addAssociatedJobRequest', data)
                            .then((response) => {
                                console.log('addAssociatedJobRequest fire events -> refreshCSAssociatedJobsListTable');
                                this.$events.fire('refreshCSAssociatedJobsListTable');
                            })
                            .catch((error) => {});

                    }
                    else
                    {
                        this.$emit('onCloseNewModal');
                        this.$store.dispatch('showErrorNotification', 'Sorry, no jobs are added because you have not selected any jobs!');
                    }

                },
                onClickCancel() {
                    console.log('onClickCancel');
                    this.$emit('onCloseNewModal');
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


