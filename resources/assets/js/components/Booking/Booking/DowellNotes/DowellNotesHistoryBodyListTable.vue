<template>
    <div>
        <div>
            <div class="pull-left">
                <dowell-notes-history-filter-bar></dowell-notes-history-filter-bar>
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

        <dowell-notes-history-vue-table ref="dowellNotesHistoryVueTable"
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
        ></dowell-notes-history-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="dowellNotesHistorypaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="dowellNotesHistorypagination"
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
    import DowellNotesHistoryBodyFilterBar from './DowellNotesHistoryBodyFilterBar.vue'
//    import DowellNotesHistoryBodyActions from './DowellNotesHistoryBodyActions.vue'

//    Vue.component('dowell-notes-history-actions', DowellNotesHistoryBodyActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                //selectedOrder: state => state.tab.selectedOrder,
            }),
            url(){
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId: this.quoteId,
                    location: this.location,
                };
                return api.currentDowellNotesHistoryList;
            },
        },
        props: {
            orderId : '',
            quoteId: '',
            location: '',
        },
        mounted() {
        },
        components: {
            'dowell-notes-history-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'dowell-notes-history-filter-bar': DowellNotesHistoryBodyFilterBar,
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
                        sortField: 'comments.updated_at',
                    },
                    {
                        title: 'Note Type',
                        name: 'note_type.name',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
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
                this.$refs.dowellNotesHistorypagination.setPaginationData(paginationData);
                this.$refs.dowellNotesHistorypaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.dowellNotesHistoryVueTable.changePage(page)
            },
            onActions (data) {
                console.log('dowellNotesHistoryTable onActions', data);
                console.log('dowellNotesHistoryTable onActions data.data.id=', data.data.id);
            },
        },
        events: {
            'dowell-notes-history-list-filter-set' (filterData) {
                console.log('dowell-notes-history-list-filter-set filter Data=', filterData);
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId: this.quoteId,
                    location: this.location,
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.dowellNotesHistoryVueTable)
                        this.$refs.dowellNotesHistoryVueTable.refresh();
                });
            },
            'dowell-notes-history-list-filter-reset' () {
                console.log('dowell-notes-history-list-filter-reset');
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId: this.quoteId,
                    location: this.location,
                };
                Vue.nextTick( () => this.$refs.dowellNotesHistoryVueTable.refresh() )
            },
            'refreshDowellNotesHistoryTable'() {
                console.log('events -> refreshDowellNotesHistoryTable');
                Vue.nextTick( () => this.$refs.dowellNotesHistoryVueTable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.dowellNotesHistoryVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.dowellNotesHistorypagination.setPaginationData(this.$refs.dowellNotesHistoryVueTable.tablePagination)
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
