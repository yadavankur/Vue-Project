<template>
    <div>
        <div>
            <div class="pull-left">
                <expedite-history-filter-bar></expedite-history-filter-bar>
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

        <expedite-history-vue-table ref="expediteHistoryVueTable"
                                 :api-url="url"
                                 :fields="fields"
                                 :per-page="perPage"
                                 :pagination-path="paginationPath"
                                 :css="css.table"
                                 :sort-order="sortOrder"
                                 trackBy="id"
                                 :multi-sort="true"
                                 detail-row-component="expedite-history-detail-row"
                                 @vuetable:cell-clicked="onCellClicked"
                                 :append-params="moreParams"
                                 @CustomAction:action-item="onActions"
                                 @vuetable:pagination-data="onPaginationData"
        ></expedite-history-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="expediteHistorypaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="expediteHistorypagination"
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
    import ExpediteHistoryBodyFilterBar from './ExpediteHistoryBodyFilterBar.vue'
    import ExpediteHistoryBodyActions from './ExpediteHistoryBodyActions.vue'
    import ExpediteHistoryBodyDetailRow from './ExpediteHistoryBodyDetailRow.vue'
    Vue.component('expedite-history-actions', ExpediteHistoryBodyActions);
    Vue.component('expedite-history-detail-row', ExpediteHistoryBodyDetailRow);
    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                //selectedOrder: state => state.tab.selectedOrder,
            }),
            url(){
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId:  this.quoteId,
                    location:  this.location,
                    type: this.expediteType? this.expediteType : 'EXPEDITE',
                };
                return api.currentEmailHistoryList;
            },
        },
        props: {
            expediteType: '',
            orderId: '',
            quoteId:  '',
            location:  '',
        },
        mounted() {
        },
        components: {
            'expedite-history-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'expedite-history-filter-bar': ExpediteHistoryBodyFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'From',
                        name: 'from',
                        sortField: 'from',
                    },
                    {
                        title: 'Subject',
                        name: 'subject',
                        sortField: 'subject',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Status',
                        name: 'status',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Sent By',
                        name: 'created_by.name',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Date Sent',
                        name: 'updated_at',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
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
                    { field: 'updated_at', sortField: 'emails.updated_at', direction: 'desc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.expediteHistorypagination.setPaginationData(paginationData);
                this.$refs.expediteHistorypaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.expediteHistoryVueTable.changePage(page)
            },
            onActions (data) {
                console.log('expediteHistoryTable onActions', data);
                console.log('expediteHistoryTable onActions data.data.id=', data.data.id);
                this.$refs.expediteHistoryVueTable.toggleDetailRow(data.id);
            },
            onCellClicked (data, field, event) {
                console.log('cellClicked: ', field.name);
                this.$refs.expediteHistoryVueTable.toggleDetailRow(data.id)
            },
        },
        events: {
            'expedite-history-list-filter-set' (filterData) {
                console.log('expedite-history-list-filter-setfilter Data=', filterData);
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId:  this.quoteId,
                    location:  this.location,
                    type: this.expediteType? this.expediteType : 'EXPEDITE',
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.expediteHistoryVueTable)
                        this.$refs.expediteHistoryVueTable.refresh();
                });
            },
            'expedite-history-list-filter-reset' () {
                console.log('expedite-history-list-filter-reset');
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId:  this.quoteId,
                    location:  this.location,
                    type: this.expediteType? this.expediteType : 'EXPEDITE',
                };
                Vue.nextTick( () => this.$refs.expediteHistoryVueTable.refresh() )
            },
            'refreshExpediteHistoryTable'() {
                console.log('events -> refreshExpediteHistoryTable');
                Vue.nextTick( () => this.$refs.expediteHistoryVueTable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.expediteHistoryVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.expediteHistorypagination.setPaginationData(this.$refs.expediteHistoryVueTable.tablePagination)
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
