<template>
    <div>
        <div>
            <div class="pull-left">
                <cpm-activity-notes-history-filter-bar></cpm-activity-notes-history-filter-bar>
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

        <cpm-activity-notes-history-vue-table ref="cpmActivityNotesHistoryVueTable"
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
        ></cpm-activity-notes-history-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="cpmActivityNotesHistorypaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="cpmActivityNotesHistorypagination"
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
    import CPMActivityNotesHistoryBodyFilterBar from './CPMActivityNotesHistoryBodyFilterBar.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            url(){
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    activityId: this.activityId,
                };
                return api.currentCPMActivityNotesHistoryList;
            },
        },
        props: {
            orderId : '',
            quoteId: '',
            locationId: '',
            activityId: '',
        },
        mounted() {
        },
        components: {
            'cpm-activity-notes-history-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cpm-activity-notes-history-filter-bar': CPMActivityNotesHistoryBodyFilterBar,
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
                        sortField: 'cpm_comments.updated_at',
                    },
                    {
                        title: 'Note Type',
                        name: 'type',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Comments',
                        name: 'comment',
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
                    { field: 'updated_at', sortField: 'cpm_comments.updated_at', direction: 'desc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.cpmActivityNotesHistorypagination.setPaginationData(paginationData);
                this.$refs.cpmActivityNotesHistorypaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.cpmActivityNotesHistoryVueTable.changePage(page)
            },
            onActions (data) {
                console.log('cpmActivityNotesHistoryTable onActions', data);
                console.log('cpmActivityNotesHistoryTable onActions data.data.id=', data.data.id);
            },
        },
        events: {
            'cpm-activity-notes-history-list-filter-set' (filterData) {
                console.log('cpm-activity-notes-history-list-filter-set filter Data=', filterData);
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    activityId: this.activityId,
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.cpmActivityNotesHistoryVueTable)
                        this.$refs.cpmActivityNotesHistoryVueTable.refresh();
                });
            },
            'cpm-activity-notes-history-list-filter-reset' () {
                console.log('cpm-activity-notes-history-list-filter-reset');
                this.moreParams = {
                    orderId: this.orderId,
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    activityId: this.activityId,
                };
                Vue.nextTick( () => {
                    if (this.$refs.cpmActivityNotesHistoryVueTable)
                        this.$refs.cpmActivityNotesHistoryVueTable.refresh()
                } )
            },
            'refreshCPMActivityNotesHistoryTable'() {
                console.log('events -> refreshCPMActivityNotesHistoryTable');
                Vue.nextTick( () => {
                    if (this.$refs.cpmActivityNotesHistoryVueTable)
                        this.$refs.cpmActivityNotesHistoryVueTable.refresh()
                } );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cpmActivityNotesHistoryVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cpmActivityNotesHistorypagination.setPaginationData(this.$refs.cpmActivityNotesHistoryVueTable.tablePagination)
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
