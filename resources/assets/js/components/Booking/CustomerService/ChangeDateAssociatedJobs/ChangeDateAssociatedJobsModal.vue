<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" large>
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Associated jobs </h4>
                </div>
                <div slot="modal-body" class="modal-body table-responsive">
                    <cd-associated-jobs-list-table v-if="isShowModal"
                                                   ref="cdAssociatedJobs"
                                                   :selectedOrder="selectedOrder"
                                                   @onReBookJob="onReBookJob"
                    ></cd-associated-jobs-list-table>
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
    import ChangeDateAssociatedJobsListTable from './ChangeDateAssociatedJobsListTable.vue'

    export default {
        props: {
            isShowChangeDateModal: false,
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
                this.isVisible = this.isShowChangeDateModal;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
                //selectedOrder: state => state.customerService.selectedOrder,
            }),
        },
        created() {
            console.log('CDAssociatedJobsNewModal Component created.')
        },
        components: {
            'cd-associated-jobs-list-table': ChangeDateAssociatedJobsListTable,
            'custom-modal': modal,
        },
        mounted() {
            console.log('CDAssociatedJobsNewModal Component mounted.')
        },
        methods:
        {
            onReBookJob() {
                console.log('CDAssociatedJobsNewModal -> onReBookJob');
                this.$emit('onReBookJob');
            },
            onClickOk() {
                console.log('onClickOk');
                this.$emit('onCloseChangeDateModal');
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseChangeDateModal');
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