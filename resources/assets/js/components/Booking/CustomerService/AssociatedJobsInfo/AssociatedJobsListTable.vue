<template>
    <div>
        <cs-book-associated-jobs
                :key="selectedOrder"
                :selectedOrder="selectedOrder"
        >
        </cs-book-associated-jobs>
        <cs-email-preparation-modal
                :isShowEmail="isShowEmail"
                :order="selectedJob? selectedJob: null"
                :jobIds="selectedJobIds"
                :emailType="emailType"
                @onCloseEmailModal="onCloseEmailModal"
        >
        </cs-email-preparation-modal>
        <cs-text-message-modal
                :isShowTextMsg="isShowTextMsg"
                :order="selectedJob? selectedJob: null"
                @onCloseTextMessageModal="onCloseTextMessageModal"
        >
        </cs-text-message-modal>
        <div class="row-content">
            <div class="pull-left">
                <cs-associated-jobs-filter-bar
                    @onEmailJobsList="onEmailJobsList"
                    @onPrintJobsList="onPrintJobsList"
                    @onOpenBookAssociatedJobs="onOpenBookAssociatedJobs"
                    :selectedIds="selectedIds">
                </cs-associated-jobs-filter-bar>
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
        <cs-associated-jobs-vue-table ref="csassociatedJobsVueTable"
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
            <template slot="cs-slot-action" scope="props">
                <div>{{ props.rowData.confirmed_date ? (props.rowData.booking? props.rowData.booking.updated_by.name : '') : ''}}</div>
            </template>
        </cs-associated-jobs-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="csassociatedJobspaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="csassociatedJobspagination"
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
    import CSAssociatedJobsFilterBar from './AssociatedJobsFilterBar.vue'
    import CSAssociatedJobsActions from './AssociatedJobsActions.vue'
//    import CSEmailPreparationModal from '../Email/EmailPreparationModal.vue'
//    import CSTextMessageModal from '../TextMessage/TextMessageModal.vue'
    import CSEmailPreparationModal from '../../Confirmation/Email/EmailPreparationModal.vue'
    import CSTextMessageModal from '../../Confirmation/TextMessage/TextMessageModal.vue'
    import CSBookAssociatedJobs from '../ChangeDateAssociatedJobs/BookAssociatedJobs.vue'
    import CSAssociatedJobsDetailRow from './AssociatedJobsDetailedRow.vue'
    Vue.component('cs-associated-jobs-actions', CSAssociatedJobsActions);
    Vue.component('cs-associated-jobs-detail-row', CSAssociatedJobsDetailRow);
    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.customerService.selectedOrder,
            }),
        },
        props: {
        },
        mounted() {
        },
        components: {
            'cs-associated-jobs-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cs-associated-jobs-filter-bar': CSAssociatedJobsFilterBar,
            'cs-email-preparation-modal': CSEmailPreparationModal,
            'cs-text-message-modal': CSTextMessageModal,
            'cs-book-associated-jobs': CSBookAssociatedJobs,
        },
        data () {
            return {
                isShowEmail: false,
                isShowTextMsg: false,
                selectedIds: [],
                selectedJobIds: [],
                selectedJob: null,
                emailType: 'single', // single or list
                url:'',
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
                    {
                        title: 'Original Delivery Date',
                        name: 'DELIVERY_DATE',
                        sortField: 'DELIVERY_DATE',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Agreed Delivery Date',
                        name: 'agreed_date',
                        sortField: 'booking_confirmation.agreed_date',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'BOOKED',
                        name: 'booked',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatCheckBox',
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
                        name: '__slot:cs-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        name: '__component:cs-associated-jobs-actions',
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
            onCellClicked (data, field, event) {
                console.log('cellClicked: ', field.name);
                this.$refs.csassociatedJobsVueTable.toggleDetailRow(data.id)
            },
            onOpenBookAssociatedJobs() {
                this.$events.fire('open-book-associated-jobs');
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
                this.selectedIds = this.$refs.csassociatedJobsVueTable.selectedTo;
                console.log('onCheckboxToggled onActions selectedIds=', this.selectedIds);
            },
            onCheckboxToggledAll(isChecked) {
                console.log('onCheckboxToggledAll onActions isChecked=', isChecked);
                this.selectedIds = this.$refs.csassociatedJobsVueTable.selectedTo;
                console.log('onCheckboxToggledAll onActions selectedIds=', this.selectedIds);
            },
            formatCheckBox(value) {
                return '<div align="center"><input type="checkbox"' + (parseInt(value)? 'checked': '')  + ' disabled></div>';
            },
            onPaginationData (paginationData) {
                this.$refs.csassociatedJobspagination.setPaginationData(paginationData);
                this.$refs.csassociatedJobspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.csassociatedJobsVueTable.changePage(page)
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
                console.log('csassociatedJobsTable onActions', data);
                console.log('csassociatedJobsTable onActions data.data.id=', data.data.id);
                // send single email or text single message
                if ( data && data.data.id)
                {
                    switch (data.action)
                    {
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
            'cs-associated-jobs-list-filter-set' (filterData) {
                console.log('cs-associated-jobs-list-filter-set Data=', filterData);
                this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    filter: filterData
                };
                this.selectedIds=[];
                Vue.nextTick( () => {
                    if (this.$refs.csassociatedJobsVueTable)
                        this.$refs.csassociatedJobsVueTable.refresh();
                });
            },
            'cs-associated-jobs-list-filter-reset' () {
                console.log('cs-associated-jobs-list-filter-reset');
                this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                };
                this.selectedIds=[];
                Vue.nextTick( () => this.$refs.csassociatedJobsVueTable.refresh() );
            },
            'refreshCSAssociatedJobsListTable'() {
                this.selectedIds=[];
                console.log('events -> refreshCSAssociatedJobsListTable');
                Vue.nextTick( () => this.$refs.csassociatedJobsVueTable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.csassociatedJobsVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.csassociatedJobspagination.setPaginationData(this.$refs.csassociatedJobsVueTable.tablePagination)
                })
            },
            url() {
                console.log('watch +++++++ url() = ', this.url);
            },
        },
    }
</script>
<style scoped>
    /*.pagination {*/
        /*margin-top: 5px;*/
        /*margin-bottom: 15px;*/
        /*float: right;*/
    /*}*/
    /*.pagination a.page {*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 10px;*/
        /*margin-right: 2px;*/
    /*}*/
    /*.pagination a.page.active {*/
        /*color: white;*/
        /*background-color: #337ab7;*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 10px;*/
        /*margin-right: 2px;*/
    /*}*/
    /*.pagination a.btn-nav {*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 7px;*/
        /*margin-right: 2px;*/
    /*}*/
    /*.pagination a.btn-nav.disabled {*/
        /*color: lightgray;*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 7px;*/
        /*margin-right: 2px;*/
        /*cursor: not-allowed;*/
    /*}*/
    /*.pagination-info {*/
        /*margin-top: 5px;*/
        /*margin-bottom: 15px;*/
        /*float: left;*/
    /*}*/
    /*.form-control {*/
    /*height: 34px !important;*/
    /*}*/
    .filter-bar {
        padding: 10px;
    }
    label {
        margin-top: 10px !important;
    }
    /*.row-content {*/
        /*padding: 10px;*/
    /*}*/
</style>
