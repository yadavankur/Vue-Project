<template>
    <div class="booking-associated-jobs">
        <cd-change-date-associated-jobs-modal
                :isShowChangeDateModal="isShowChangeDateModal"
                :selectedOrder="selectedOrder"
                @onReBookJob="onOpenRebookJobModal"
                @onCloseChangeDateModal="onCloseChangeDateModal">
        </cd-change-date-associated-jobs-modal>
        <cd-email-preparation-modal
                :isShowEmail="isShowEmail"
                :order="selectedOrder? selectedOrder.order: null"
                :jobIds="selectedJobIds"
                @onCloseEmailModal="onCloseEmailModal"
        >
        </cd-email-preparation-modal>
        <cd-text-message-modal
                :isShowTextMsg="isShowTextMsg"
                :order="selectedOrder? selectedOrder.order: null"
                @onCloseTextMessageModal="onCloseTextMessageModal"
        >
        </cd-text-message-modal>
        <cd-rebook-job-modal
                :isShowRebookJob="isShowRebookJob"
                :selectedOrder="selectedOrder"
                @onCloseRebookJobModal="onCloseRebookJobModal">
        </cd-rebook-job-modal>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import CDChangeDateAssociatedJobsModal from '../ChangeDateAssociatedJobs/ChangeDateAssociatedJobsModal.vue'
    import CDEmailPreparationModal from '../Email/EmailPreparationModal.vue'
    import CDTextMessageModal from '../TextMessage/TextMessageModal.vue'
    import CDReBookJobModal from './ReBookJobModal.vue'

    export default {
        props: {
            selectedOrder: {
                type: Object,
            },
//            selectedJobIds: {
//                type: Array,
//            }
        },
        data () {
            return {
                isShowChangeDateModal: false,
                isShowEmail: false,
                isShowTextMsg: false,
                isShowRebookJob: false,
                selectedJobIds: [],
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('BookAssociatedJobs Component created.')
        },
        components: {
            'cd-change-date-associated-jobs-modal': CDChangeDateAssociatedJobsModal,
            'cd-email-preparation-modal': CDEmailPreparationModal,
            'cd-text-message-modal': CDTextMessageModal,
            'cd-rebook-job-modal': CDReBookJobModal,
        },
        mounted() {
            console.log('BookAssociatedJobs Component mounted.')
        },
        events: {
            'open-book-associated-jobs' () {
                console.log('open-book-associated-jobs event fired');
                this.onOpenChangeDateModal();
            },
            'open-book-associated-jobs-email'(selectedJobIds) {
                console.log('open-book-associated-jobs-email event fired. selectedJobIds=', selectedJobIds);
                this.selectedJobIds = selectedJobIds;
                this.onOpenEmailModal();
            },
            'open-book-associated-jobs-text-message'(selectedJobIds) {
                console.log('open-book-associated-jobs-text-message event fired. selectedJobIds=', selectedJobIds);
                this.selectedJobIds = selectedJobIds;
                this.onOpenTextMessageModal();
            }
        },
        methods:
        {
            onOpenChangeDateModal() {
                console.log('onOpenChangeDateModal.');
                this.isShowChangeDateModal = true;
            },
            onCloseChangeDateModal() {
                console.log('onCloseChangeDateModal.');
                this.isShowChangeDateModal = false;
            },
            onOpenEmailModal() {
                console.log('onOpenEmailModal.');
                this.isShowEmail = true;
            },
            onCloseEmailModal() {
                console.log('onCloseEmailModal.');
                this.isShowEmail = false;
            },
            onOpenTextMessageModal() {
                console.log('onOpenTextMessageModal.')
                this.isShowTextMsg = true;
            },
            onCloseTextMessageModal() {
                console.log('onCloseTextMessageModal.');
                this.isShowTextMsg = false;
            },
            onOpenRebookJobModal() {
                console.log('onOpenRebookJobModal.');
                this.isShowRebookJob = true;
            },
            onCloseRebookJobModal() {
                console.log('onCloseRebookJobModal.');
                this.isShowRebookJob = false;
            },
        }
    }
</script>

<style scoped>

</style>