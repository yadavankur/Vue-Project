<template>
    <div >
        <div>
            <div class="pull-left">
                <task-group-list-filter-bar></task-group-list-filter-bar>
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

        <vuetable ref="taskGroupListVuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  trackBy="id"
                  :sort-order="sortOrder"
                  :multi-sort="true"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="taskGroupPaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="taskGroupPagination"
                                 :css="css.pagination"
                                 :icons="css.icons"
                                 @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import * as api from './../../../config';
    import TaskGroupListFilterBar from './TaskGroupListFilterBar.vue'

    export default {
        computed: {
            url(){
                this.moreParams = {
                    type: 'GROUP',
                };
                return api.currentTaskList;
            },
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser
            }),
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'task-group-list-filter-bar': TaskGroupListFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Sales Order No',
                        name: 'order_id',
                        titleClass: 'text-right',
                        dataClass: 'text-right'
                    },
                    {
                        title: 'Task Name',
                        name: 'name',
                    },
                    {
                        title: 'Status',
                        name: 'status_id',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                        callback: 'setStatusName',
                    },
                    {
                        title: 'Due Day',
                        name: 'end_date',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                        callback: 'formatDate|DD/MM/YYYY',
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
                    { field: 'end_date', sortField: 'cpm_order_matrixes.end_date', direction: 'desc'}
                ],
                moreParams: {
                }
            }
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.taskGroupPagination.setPaginationData(paginationData);
                this.$refs.taskGroupPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.taskGroupListVuetable.changePage(page)
            },
        },
        events: {
            'task-group-list-filter-set' (filterText) {
                this.moreParams = {
                    type: 'GROUP',
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.taskGroupListVuetable.refresh() )
            },
            'task-group-list-filter-reset' () {
                this.moreParams = {
                    type: 'GROUP',
                }
                Vue.nextTick( () => this.$refs.taskGroupListVuetable.refresh() )
            },
            'refreshTaskGroupListTable'() {
                console.log('events -> refreshTaskGroupListTable');
                Vue.nextTick( () => this.$refs.taskGroupListVuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.taskGroupListVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.taskGroupPagination.setPaginationData(this.$refs.taskGroupListVuetable.tablePagination)
                })
            },
        },
    }
</script>

<style>
</style>
