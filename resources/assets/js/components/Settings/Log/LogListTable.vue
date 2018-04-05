<template>
    <div>
        <div class="row-content">
            <div class="pull-left">
                <log-filter-bar>
                </log-filter-bar>
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
            <log-vue-table ref="logVueTable"
                                       :api-url="url"
                                       :fields="fields"
                                       :per-page="perPage"
                                       :pagination-path="paginationPath"
                                       :css="css.table"
                                       :sort-order="sortOrder"
                                       trackBy="id"
                                       :multi-sort="true"
                                       :append-params="moreParams"
                                       @vuetable:pagination-data="onPaginationData">
            </log-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="logPaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="logPagination"
                                     :css="css.pagination"
                                     :icons="css.icons"
                                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import * as api from '../../../config';
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import LogFilterBar from './LogFilterBar.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        props: {
        },
        mounted() {
        },
        created() {
            console.log('LogListTable -> created');
        },
        components: {
            'log-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'log-filter-bar': LogFilterBar,
        },
        data () {
            return {
                url: api.currentLogList,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Type',
                        name: 'type',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Message',
                        name: 'message',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Entity Name',
                        name: 'entity_name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Entity ID',
                        name: 'entity_id',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Function Name',
                        name: 'function_name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Level',
                        name: 'level',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Updated By',
                        name: 'updated_by.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Updated At',
                        name: 'updated_at',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered',
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
                    { field: 'id', sortField: 'id', direction: 'desc'}
                ],
                moreParams: {
                    filter: {
                        type: 'USER_ACTION',
                        level: 'ERROR',
                    },
                },
            }
        },
        methods: {
            onPaginationData (paginationData) {
                console.log('LogListTable -> onPaginationData');
                this.$refs.logPagination.setPaginationData(paginationData);
                this.$refs.logPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('LogListTable -> onChangePage');
                this.$refs.logVueTable.changePage(page)
            },
        },
        events: {
            'log-list-filter-set' (filterData) {
                console.log('log-list-filter-set Data=', filterData);
                this.moreParams = {
                    filter: filterData,
                };
                Vue.nextTick( () => {
                    if (this.$refs.logVueTable)
                        this.$refs.logVueTable.refresh();
                });
            },
            'log-list-filter-reset' () {
                console.log('log-list-filter-reset');
                this.moreParams = {
                    filter: '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.logVueTable)
                        this.$refs.logVueTable.refresh()
                });
            },
            'refreshLogListTable'() {
                console.log('events -> refreshLogListTable');
                Vue.nextTick( () => {
                    if (this.$refs.logVueTable)
                        this.$refs.logVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('LogListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.logVueTable)
                        this.$refs.logVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('LogListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.logPagination.setPaginationData(this.$refs.logVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>
