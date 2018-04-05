<template>
    <div>
        <div>
            <div class="pull-left">
                <associated-jobs-all-filter-bar :quoteNumber="selectedOrder.QUOTE_NUM"></associated-jobs-all-filter-bar>
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

        <associated-jobs-all-vue-table ref="associatedJobsAllVueTable"
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
        ></associated-jobs-all-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="associatedJobsAllpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="associatedJobsAllpagination"
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
    import AssociatedJobsAllFilterBar from './AssociatedJobsAllFilterBar.vue'
    import AssociatedJobsAllActions from './AssociatedJobsAllActions.vue'
    import moment from 'moment'

    Vue.component('associated-jobs-all-actions', AssociatedJobsAllActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.tab.selectedOrder,
            }),
//            url(){
//                this.moreParams = {
//                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
//                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
//                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
//                };
//                return api.currentAssociatedJobsAll;
//            },
        },
        props: {
        },
        created() {
            this.moreParams = {
                orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                filter: this.selectedOrder? this.selectedOrder.QUOTE_NUM : '',
            };
        },
        mounted() {
        },
        components: {
            'associated-jobs-all-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'associated-jobs-all-filter-bar': AssociatedJobsAllFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                url: api.currentAssociatedJobsAll,
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
                        //sortField: 'jobNo',
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
                this.$refs.associatedJobsAllpagination.setPaginationData(paginationData);
                this.$refs.associatedJobsAllpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.associatedJobsAllVueTable.changePage(page)
            },
            onActions (data) {
                console.log('associatedJobsAllTable onActions', data);
            },
        },
        events: {
            'associated-jobs-all-filter-set' (filterData) {
                console.log('associated-jobs-all-filter-set filter Data=', filterData);
                //this.url = api.currentAssociatedJobsAll;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.associatedJobsAllVueTable)
                        this.$refs.associatedJobsAllVueTable.refresh();
                });
            },
            'associated-jobs-all-filter-reset' () {
                console.log('associated-jobs-all-filter-reset');
                //this.url = api.currentAssociatedJobsAll;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    filter: this.selectedOrder? this.selectedOrder.QUOTE_NUM : '',
                };
                Vue.nextTick( () => this.$refs.associatedJobsAllVueTable.refresh() )
            },
            'refreshAssociatedJobsAllTable'() {
                console.log('events -> refreshAssociatedJobsAllTable');
                Vue.nextTick( () => this.$refs.associatedJobsAllVueTable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.associatedJobsAllVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.associatedJobsAllpagination.setPaginationData(this.$refs.associatedJobsAllVueTable.tablePagination)
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
