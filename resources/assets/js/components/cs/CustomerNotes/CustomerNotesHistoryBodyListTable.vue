<template>
    <div>
        <div>
            <div class="pull-left">
                <customer-notes-history-filter-bar></customer-notes-history-filter-bar>
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

        <customer-notes-history-vue-table ref="customerNotesHistoryVueTable"
                                 :api-url="url"
                                 :fields="fields"
                                 :per-page="perPage"
                                 :pagination-path="paginationPath"
                                 :css="css.table"
                                 :sort-order="sortOrder"
                                 trackBy="id"
                                 :multi-sort="true"
                                 :append-params="moreParams"
                                 @CustomAction:action-item="onActions"
                                 @vuetable:pagination-data="onPaginationData"
        ></customer-notes-history-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="customerNotesHistorypaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="customerNotesHistorypagination"
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
    import * as api from '../../../config';
    import CustomerNotesHistoryBodyFilterBar from './CustomerNotesHistoryBodyFilterBar.vue'
    import CustomerNotesHistoryBodyActions from './CustomerNotesHistoryBodyActions.vue'

    Vue.component('customer-notes-history-actions', CustomerNotesHistoryBodyActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.tab.selectedOrder,
            }),
            url(){
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                return api.currentCustomerNotesHistoryList;
            },
        },
        props: {
        },
        mounted() {
        },
        components: {
            'customer-notes-history-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'customer-notes-history-filter-bar': CustomerNotesHistoryBodyFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Input Date',
                        name: 'updated_at',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'updated_at',
                    },
                    {
                        title: 'Comments',
                        name: 'comments',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Input By',
                        name: 'created_by.name',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
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
                    { field: 'updated_at', sortField: 'comments.updated_at', direction: 'desc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.customerNotesHistorypagination.setPaginationData(paginationData);
                this.$refs.customerNotesHistorypaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.customerNotesHistoryVueTable.changePage(page)
            },
            onActions (data) {
                console.log('customerNotesHistoryTable onActions', data);
                console.log('customerNotesHistoryTable onActions data.data.id=', data.data.id);
            },
        },
        events: {
            'customer-notes-history-list-filter-set' (filterData) {
                console.log('customer-notes-history-list-filter-set filter Data=', filterData);
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                    quoteId:  this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location:  this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    filter: filterData,
                };
                Vue.nextTick( () => {
                    if (this.$refs.customerNotesHistoryVueTable)
                        this.$refs.customerNotesHistoryVueTable.refresh();
                });
            },
            'customer-notes-history-list-filter-reset' () {
                console.log('customer-notes-history-list-filter-reset');
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                    quoteId:  this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location:  this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                Vue.nextTick( () => this.$refs.customerNotesHistoryVueTable.refresh() )
            },
            'refreshCustomerNotesHistoryTable'() {
                console.log('events -> refreshCustomerNotesHistoryTable');
                Vue.nextTick( () => this.$refs.customerNotesHistoryVueTable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.customerNotesHistoryVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.customerNotesHistorypagination.setPaginationData(this.$refs.customerNotesHistoryVueTable.tablePagination)
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
        margin-top: 5px;
        margin-bottom: 15px;

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
        margin-top: 5px;
        margin-bottom: 15px;
        float: left;
    }
    .filter-bar {
        padding: 10px;
    }
    label {
        margin-top: 10px !important;
    }
</style>
