<template>
    <div>
        <div>
            <div class="pull-left">
                <cs-associated-jobs-all-filter-bar></cs-associated-jobs-all-filter-bar>
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

        <cs-associated-jobs-all-vue-table ref="csassociatedJobsAllVueTable"
                                       :api-url="url"
                                       :fields="fields"
                                       :per-page="perPage"
                                       :pagination-path="paginationPath"
                                       :css="css.table"
                                       :sort-order="sortOrder"
                                       trackBy="QUOTE_ID"
                                       :multi-sort="true"
                                       :append-params="moreParams"
                                       @CustomAction:action-item="onActions"
                                       @vuetable:pagination-data="onPaginationData"
        ></cs-associated-jobs-all-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="csassociatedJobsAllpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="csassociatedJobsAllpagination"
                                 :css="css.pagination"
                                 :icons="css.icons"
                                 @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
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
    import CSAssociatedJobsAllFilterBar from './AssociatedJobsAllFilterBar.vue'
    import CSAssociatedJobsAllActions from './AssociatedJobsAllActions.vue'

    Vue.component('cs-associated-jobs-all-actions', CSAssociatedJobsAllActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.customerService.selectedOrder,
            }),
            url(){
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                return api.currentAssociatedJobsAll;
            },
        },
        props: {
        },
        mounted() {
        },
        components: {
            'cs-associated-jobs-all-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cs-associated-jobs-all-filter-bar': CSAssociatedJobsAllFilterBar,
        },
        data () {
            return {
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
            onPaginationData (paginationData) {
                this.$refs.csassociatedJobsAllpagination.setPaginationData(paginationData);
                this.$refs.csassociatedJobsAllpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.csassociatedJobsAllVueTable.changePage(page)
            },
            onActions (data) {
                console.log('csassociatedJobsAllTable onActions', data);
            },
        },
        events: {
            'cs-associated-jobs-all-filter-set' (filterData) {
                console.log('cs-associated-jobs-all-filter-set filter Data=', filterData);
                //this.url = api.currentAssociatedJobsAll;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.csassociatedJobsAllVueTable)
                        this.$refs.csassociatedJobsAllVueTable.refresh();
                });
            },
            'cs-associated-jobs-all-filter-reset' () {
                console.log('cs-associated-jobs-all-filter-reset');
                //this.url = api.currentAssociatedJobsAll;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.csassociatedJobsAllVueTable)
                        this.$refs.csassociatedJobsAllVueTable.refresh();
                })
            },
            'refreshCSAssociatedJobsAllTable'() {
                console.log('events -> refreshCSAssociatedJobsAllTable');
                Vue.nextTick( () => this.$refs.csassociatedJobsAllVueTable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.csassociatedJobsAllVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.csassociatedJobsAllpagination.setPaginationData(this.$refs.csassociatedJobsAllVueTable.tablePagination)
                })
            },
            url() {
                console.log('watch +++++++ url() = ', this.url);
            },
        },
    }
</script>
<style scoped>
    .pagination {
        margin: 5px;
        float: right;
    }
    .pagination a.page {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.page.active {
        color: white;
        background-color: #337ab7;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.btn-nav {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
    }
    .pagination a.btn-nav.disabled {
        color: lightgray;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
        cursor: not-allowed;
    }
    .pagination-info {
        float: left;
    }
    .filter-bar {
        padding: 10px;
    }
    label {
        margin-top: 10px !important;
    }
</style>
