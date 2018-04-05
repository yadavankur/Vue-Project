<template>
    <div>
        <update-confirmed-date-modal
                :isShowUpdateConfirmedDate="isShowUpdateConfirmedDate"
                :itemData="itemData"
                @onCloseUpdateConfirmedDateModal="onCloseUpdateConfirmedDateModal">
        </update-confirmed-date-modal>
        <email-preparation-modal
                :isShowEmail="isShowEmail"
                :order="selectedJob? selectedJob: null"
                :jobIds="selectedJobIds"
                :emailType="emailType"
                @onCloseEmailModal="onCloseEmailModal"
        >
        </email-preparation-modal>
        <text-message-modal
                :isShowTextMsg="isShowTextMsg"
                :order="selectedJob? selectedJob: null"
                @onCloseTextMessageModal="onCloseTextMessageModal"
        >
        </text-message-modal>
        <div class="row-content">
            <div class="pull-left">
                <confirmation-associated-jobs-filter-bar
                    @onEmailJobsList="onEmailJobsList"
                    @onPrintJobsList="onPrintJobsList"
                    :selectedIds="this.selectedIds">
                </confirmation-associated-jobs-filter-bar>
            </div>
            <div class="pull-right">
                <div class="filter-bar">
                    <form class="form-inline form-group-sm">
                        <label>Per Page:</label>
                        <select class="form-control dropdown" v-model="perPage">
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="row-content">
        <confirmation-associated-jobs-vue-table ref="associatedJobsVueTable"
                                   :api-url="url"
                                   :fields="fields"
                                   :per-page="perPage"
                                   :pagination-path="paginationPath"
                                   :css="css.table"
                                   :sort-order="sortOrder"
                                   trackBy="id"
                                   :multi-sort="true"
                                   :append-params="moreParams"
                                   @vuetable:checkbox-toggled="onCheckboxToggled"
                                   @vuetable:checkbox-toggled-all="onCheckboxToggledAll"
                                   @CustomAction:action-item="onActions"
                                   @vuetable:pagination-data="onPaginationData">
            <template slot="confirmation-slot-action" scope="props">
                <div>{{ props.rowData.confirmed_date ? (props.rowData.booking? props.rowData.booking.updated_by.name: '') : ''}}</div>
            </template>
        </confirmation-associated-jobs-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="associatedJobspaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="associatedJobspagination"
                                 :css="css.pagination"
                                 :icons="css.icons"
                                 @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
        </div>
        </div>
    </div>
</template>

<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import * as api from '../../../../config';
    import AssociatedJobsFilterBar from './AssociatedJobsFilterBar.vue'
    import AssociatedJobsActions from './AssociatedJobsActions.vue'
    import EmailPreparationModal from '../Email/EmailPreparationModal.vue'
    import TextMessageModal from '../TextMessage/TextMessageModal.vue'
    import UpdateConfirmedDateModal from './UpdateConfirmedDateModal.vue'

    Vue.component('confirmation-associated-jobs-actions', AssociatedJobsActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        props: {
        },
        mounted() {
        },
        created() {
            this.moreParams = {
                quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                orderId: this.selectedOrder? this.selectedOrder.order_id: '',
            };
        },
        components: {
            'confirmation-associated-jobs-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'confirmation-associated-jobs-filter-bar': AssociatedJobsFilterBar,
            'email-preparation-modal': EmailPreparationModal,
            'text-message-modal': TextMessageModal,
            'update-confirmed-date-modal': UpdateConfirmedDateModal,
        },
        data () {
            return {
                isShowUpdateConfirmedDate: false,
                itemData: null,
                isShowEmail: false,
                isShowTextMsg: false,
                selectedIds: [],
                selectedJobIds: [],
                selectedJob: null,
                emailType: 'single', // single or list
                url: api.currentAssociatedJobsList,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        name: '__checkbox',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'SO NO',
                        name: 'jobNo',
                    },
                    {
                        title: 'Description',
                        name: 'UDF5',
                        sortField: 'UDF5',
                    },
//                    {
//                        title: 'Original Delivery Date',
//                        name: 'DELIVERY_DATE',
//                        sortField: 'DELIVERY_DATE',
//                        callback: 'formatDate|DD/MMM/YYYY',
//                    },
                    {
                        title: 'Current Delivery Date',
                        name: 'agreed_date',
                        sortField: 'booking_confirmation.agreed_date',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Booked?',
                        name: 'booked',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatCheckBox',
                    },
                    {
                        title: 'Confirmed?',
                        name: 'confirmed',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'getConfirmedStatus',
                    },
                    {
                        title: 'Confirmed Date',
                        name: 'confirmed_date',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Confirmed By',
                        name: '__slot:confirmation-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        name: '__component:confirmation-associated-jobs-actions',
                        title: 'Actions',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    }
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered table-striped table-hover',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down'
                    },
                    pagination: {
                        wrapperClass: 'pagination',
                        activeClass: 'active',
                        disabledClass: 'disabled',
                        pageClass: 'page',
                        linkClass: 'link',
                    },
                    icons: {
                        first: 'glyphicon glyphicon-step-backward',
                        prev: 'glyphicon glyphicon-chevron-left',
                        next: 'glyphicon glyphicon-chevron-right',
                        last: 'glyphicon glyphicon-step-forward',
                    },
                },
                sortOrder: [
                    { field: 'jobNo', sortField: 'V_V6_QUOTE.QUOTE_NUM_SUFF', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onCloseUpdateConfirmedDateModal() {
                console.log('onCloseUpdateConfirmedDateModal');
                this.isShowUpdateConfirmedDate = false;
                this.itemData = null;
            },
            onOpenUpdateConfirmedDateModal(data) {
                console.log('onOpenUpdateConfirmedDateModal');
                this.itemData = data;
                this.isShowUpdateConfirmedDate = true;
            },
            getConfirmedStatus(status) {
                let element = '';
                switch (parseInt(status))
                {
                    case 0:
                        element = '<span class="label label-danger" title="NO"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                    case 1:
                        element = '<span class="label label-success" title="YES"><span class="glyphicon glyphicon-ok-sign"></span></span>';
                        break;
                    default:
                        element = '<span class="label label-danger" title="NO"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                }
                return element;
            },
            getPrintConfirmationList(selectedJobIds) {
                let payload = {
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    selectedJobIds: selectedJobIds,
                    viewType: 'PRINT_LIST',
                };
                this.$store.dispatch('getPrintConfirmationList', payload)
                    .then((response) => {
                        console.log('getPrintConfirmationList response=', response);
                        this.downLoad(response);
                    })
                    .catch((error) => {
                        console.log('getPrintConfirmationList error=', error);
                    });
            },
            onEmailJobsList() {
                console.log('onEmailJobsList');
                // based on the selected jobs
                // need to pass data to email modal
                this.selectedJob = this.selectedOrder.order;
                this.selectedJobIds = this.selectedIds;
                this.emailType = 'list';
                this.onOpenEmailModal();
            },
            onPrintJobsList() {
                console.log('onPrintJobsList');
                this.selectedJobIds = this.selectedIds;
                this.getPrintConfirmationList(this.selectedJobIds);
            },
            onCheckboxToggled(isChecked, dataItem) {
                console.log('onCheckboxToggled onActions isChecked=', isChecked);
                console.log('onCheckboxToggled onActions dataItem=', dataItem);
                this.selectedIds = this.$refs.associatedJobsVueTable.selectedTo;
                console.log('onCheckboxToggled onActions selectedIds=', this.selectedIds);
            },
            onCheckboxToggledAll(isChecked) {
                console.log('onCheckboxToggledAll onActions isChecked=', isChecked);
                this.selectedIds = this.$refs.associatedJobsVueTable.selectedTo;
                console.log('onCheckboxToggledAll onActions selectedIds=', this.selectedIds);
            },
            formatCheckBox(value) {
                //return '<div align="center"><input type="checkbox"' + (parseInt(value)? 'checked': '')  + ' disabled></div>';
                let element = '';
                switch (parseInt(value))
                {
                    case 0:
                        element = '<span class="label label-warning" title="NO"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                    case 1:
                        element = '<span class="label label-success" title="YES"><span class="glyphicon glyphicon-ok-sign"></span></span>';
                        break;
                    default:
                        element = '<span class="label label-danger" title="NO"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                }
                return element;
            },
            onPaginationData (paginationData) {
                this.$refs.associatedJobspagination.setPaginationData(paginationData);
                this.$refs.associatedJobspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.associatedJobsVueTable.changePage(page)
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
                console.log('onOpenTextMessageModal.');
                this.isShowTextMsg = true;
            },
            onCloseTextMessageModal() {
                console.log('onCloseTextMessageModal.');
                this.isShowTextMsg = false;
            },
            onActions (data) {
                console.log('associatedJobsTable onActions', data);
                console.log('associatedJobsTable onActions data.data.id=', data.data.id);
                // send single email or text single message
                if ( data && data.data.id)
                {
                    switch (data.action)
                    {
                        case 'Confirm':
                            this.onOpenUpdateConfirmedDateModal(data.data);
                            break;
                        case 'Email':
                            // show email modal
                            // based on the selected job
                            // need to pass data to email modal
                            this.selectedJobIds = [];
                            this.selectedJobIds.push(data.data.id);
                            this.emailType = 'single';
                            this.selectedJob = data.data.quote;
                            this.onOpenEmailModal();
                            break;
                        case 'Text':
                            this.selectedJob = data.data.quote;
                            this.onOpenTextMessageModal();
                            break;
                    }
                }
            },
        },
        events: {
            'confirmation-main-job-confirm' (payload) {
                console.log('confirmation-main-job-confirm payload=', payload);
                let data = JSON.parse(JSON.stringify(payload));
                data.id = '';
                this.onOpenUpdateConfirmedDateModal(data);
            },
            'confirmation-associated-jobs-list-filter-set' (filterData) {
                console.log('confirmation-associated-jobs-list-filter-set Data=', filterData);
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    filter: filterData
                };
                this.selectedIds=[];
                Vue.nextTick( () => {
                    if (this.$refs.associatedJobsVueTable)
                        this.$refs.associatedJobsVueTable.refresh();
                });
            },
            'confirmation-associated-jobs-list-filter-reset' () {
                console.log('confirmation-associated-jobs-list-filter-reset');
                //this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                };
                this.selectedIds=[];
                Vue.nextTick( () => this.$refs.associatedJobsVueTable.refresh() );
            },
            'refreshConfirmationAssociatedJobsListTable'() {
                this.selectedIds=[];
                console.log('events -> refreshConfirmationAssociatedJobsListTable');
                Vue.nextTick( () => {
                    if (this.$refs.associatedJobsVueTable)
                    {
                        this.$refs.associatedJobsVueTable.refresh()
                    }
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.associatedJobsVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.associatedJobspagination.setPaginationData(this.$refs.associatedJobsVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
    .filter-bar {
        padding: 10px;
    }
    label {
        margin-top: 10px !important;
    }
</style>
