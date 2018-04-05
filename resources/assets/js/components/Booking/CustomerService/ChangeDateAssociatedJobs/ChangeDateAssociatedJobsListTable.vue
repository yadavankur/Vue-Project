<template>
    <div>
        <div class="row-content">
            <div class="pull-left">
                <cd-confirmation-associated-jobs-filter-bar
                        @onEmailJobsList="onEmailJobsList"
                        @onPrintJobsList="onPrintJobsList"
                        @onReBookJob="onReBookJob"
                        :selectedOrder="selectedOrder"
                        :selectedIds="this.selectedIds">
                </cd-confirmation-associated-jobs-filter-bar>
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
            <cd-confirmation-associated-jobs-vue-table ref="cdAssociatedJobsVueTable"
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
                <template slot="cd-confirmation-slot-action" scope="props">
                    <div>{{ props.rowData.confirmed_date ? props.rowData.updated_by.name : ''}}</div>
                </template>
            </cd-confirmation-associated-jobs-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="cdAssociatedJobspaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="cdAssociatedJobspagination"
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
    import ChangeDateAssociatedJobsFilterBar from './ChangeDateAssociatedJobsFilterBar.vue'
    import ChangeDateAssociatedJobsActions from './ChangeDateAssociatedJobsActions.vue'


    Vue.component('cd-confirmation-associated-jobs-actions', ChangeDateAssociatedJobsActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                // selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        props: {
            selectedOrder: {
                type: Object,
            }
        },
        mounted() {
        },
        components: {
            'cd-confirmation-associated-jobs-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cd-confirmation-associated-jobs-filter-bar': ChangeDateAssociatedJobsFilterBar,
        },
        data () {
            return {
                isShowEmail: false,
                isShowTextMsg: false,
                selectedIds: [],
                selectedJobIds: [],
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
                        title: 'Date',
                        name: 'DELIVERY_DATE',
                        sortField: 'DELIVERY_DATE',
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
                        name: '__slot:cd-confirmation-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        name: '__component:cd-confirmation-associated-jobs-actions',
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
                    { field: 'V_V6_QUOTE.QUOTE_NUM_SUFF', sortField: 'V_V6_QUOTE.QUOTE_NUM_SUFF', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onReBookJob() {
                console.log('ChangeDateAssociatedJobsFilterBar -> onReBookJob');
                this.$emit('onReBookJob');
            },
            onEmailJobsList() {
                console.log('ChangeDateAssociatedJobsFilterBar -> onEmailJobsList');
                // based on the selected jobs
                // need to pass data to email modal
                //this.selectedJobIds = this.selectedIds;
                //this.$events.fire('open-book-associated-jobs-email');
                this.onOpenEmailModal();
            },
            onPrintJobsList() {
                console.log('ChangeDateAssociatedJobsFilterBar -> onPrintJobsList');
                this.selectedJobIds = this.selectedIds;

            },
            onCheckboxToggled(isChecked, dataItem) {
                console.log('onCheckboxToggled onActions isChecked=', isChecked);
                console.log('onCheckboxToggled onActions dataItem=', dataItem);
                this.selectedIds = this.$refs.cdAssociatedJobsVueTable.selectedTo;
                console.log('onCheckboxToggled onActions selectedIds=', this.selectedIds);
            },
            onCheckboxToggledAll(isChecked) {
                console.log('onCheckboxToggledAll onActions isChecked=', isChecked);
                this.selectedIds = this.$refs.cdAssociatedJobsVueTable.selectedTo;
                console.log('onCheckboxToggledAll onActions selectedIds=', this.selectedIds);
            },
            formatCheckBox(value) {
                return '<div align="center"><input type="checkbox"' + (parseInt(value)? 'checked': '')  + ' disabled></div>';
            },
            onPaginationData (paginationData) {
                this.$refs.cdAssociatedJobspagination.setPaginationData(paginationData);
                this.$refs.cdAssociatedJobspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.cdAssociatedJobsVueTable.changePage(page)
            },
            onOpenEmailModal() {
                console.log('ChangeDateAssociatedJobsFilterBar -> onOpenEmailModal.');
                this.selectedJobIds = this.selectedIds;
                this.$events.fire('open-book-associated-jobs-email', this.selectedJobIds);
            },
            onOpenTextMessageModal() {
                console.log('ChangeDateAssociatedJobsFilterBar -> onOpenTextMessageModal.');
                this.$events.fire('open-book-associated-jobs-text-message',  this.selectedJobIds);
            },
            onActions (data) {
                console.log('cdAssociatedJobsTable onActions', data);
                console.log('cdAssociatedJobsTable onActions data.data.id=', data.data.id);
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
                            this.onOpenEmailModal();
                            break;
                        case 'Text':
                            this.selectedJobIds = [];
                            this.selectedJobIds.push(data.data.id);
                            this.onOpenTextMessageModal();
                            break;
                    }
                }
            },
        },
        events: {
            'cd-confirmation-associated-jobs-list-filter-set' (filterData) {
                console.log('cd-confirmation-associated-jobs-list-filter-set Data=', filterData);
                this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    filter: filterData
                };
                this.selectedIds=[];
                Vue.nextTick( () => {
                    if (this.$refs.cdAssociatedJobsVueTable)
                        this.$refs.cdAssociatedJobsVueTable.refresh();
                });
            },
            'cd-confirmation-associated-jobs-list-filter-reset' () {
                console.log('cd-confirmation-associated-jobs-list-filter-reset');
                this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                this.selectedIds=[];
                Vue.nextTick( () => {
                    if (this.$refs.cdAssociatedJobsVueTable)
                        this.$refs.cdAssociatedJobsVueTable.refresh()
                });
            },
            'refreshCDConfirmationAssociatedJobsListTable'() {
                this.selectedIds=[];
                console.log('events -> refreshCDConfirmationAssociatedJobsListTable');
                Vue.nextTick( () => {
                    if (this.$refs.cdAssociatedJobsVueTable)
                        this.$refs.cdAssociatedJobsVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cdAssociatedJobsVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cdAssociatedJobspagination.setPaginationData(this.$refs.cdAssociatedJobsVueTable.tablePagination)
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
    /*.row-content {*/
        /*padding: 10px;*/
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

</style>
