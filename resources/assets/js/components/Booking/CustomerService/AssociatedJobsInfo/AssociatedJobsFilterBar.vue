<template>
    <div class="filter-bar">
        <!--<cs-rebook-job-modal-->
                <!--:isShowRebookJob="isShowRebookJob"-->
                <!--@onCloseRebookJobModal="onCloseRebookJobModal">-->
        <!--</cs-rebook-job-modal>-->
        <cs-associated-jobs-new-modal
                :isShowNewModal="isShowNewModal"
                @onCloseNewModal="onCloseNewModal">
        </cs-associated-jobs-new-modal>
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
                <button class="btn btn-add btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="addNewJobs">Add New Jobs</button>
                <!--<button class="btn btn-change btn-sm"-->
                        <!--:disabled="selectedOrder? false: true"-->
                        <!--@click.prevent="changeDate">Change Date</button>-->
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
//    import CSReBookJobModal from './ReBookJobModal.vue'
    import CSAsscociatedJobsNewModal from './AssociatedJobsNewModal.vue'
//    import BookAssociatedJobs from '../ChangeDateAssociatedJobs/BookAssociatedJobs.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.customerService.selectedOrder,
            }),
        },
        components: {
            'bs-input': input,
//            'cs-rebook-job-modal': CSReBookJobModal,
            'cs-associated-jobs-new-modal' : CSAsscociatedJobsNewModal,
//            'cs-book-associated-jobs': BookAssociatedJobs,
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
                isShowNewModal: false,
                isShowChangeDateModal:false,
            }
        },
        created() {
        },
        events: {
            'cs-associated-jobs-reset' () {
                console.log('cs-associated-jobs-reset');
                this.resetFilter();
            },
        },
        methods: {
            addNewJobs() {
                console.log('addNewJobs');
                this.isShowNewModal = true;
            },
            changeDate() {
                console.log('changeDate');
                this.isShowChangeDateModal = true;
                this.$emit('onOpenBookAssociatedJobs');
            },
            doFilter () {
                this.$events.fire('cs-associated-jobs-list-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = '';
                this.$events.fire('cs-associated-jobs-list-filter-reset')
            },
            reBookJob() {
                console.log('reBookJob');
                this.isShowRebookJob = true;
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
            onCloseNewModal() {
                console.log('onCloseNewModal');
                this.isShowNewModal = false;
            },
            onCloseChangeDateModal() {
                console.log('onCloseNewModal');
                this.isShowChangeDateModal = false;
            }
        }
    }
</script>
<style scoped>
    .btn-add {
        color: #fff;
        background-color: #00d1b2;
        border-color: #00d1b2;
    }
    .btn-change {
        color: #fff;
        background-color: #005dd1;
        border-color: #005dd1;
    }

</style>