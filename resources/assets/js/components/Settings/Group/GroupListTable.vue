<template>
    <div>
        <div>
            <div class="pull-left">
                <group-custom-filter-bar @onClickNew="onClickNew"></group-custom-filter-bar>
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

        <vuetable ref="groupvuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  :sort-order="sortOrder"
                  :multi-sort="true"
                  @CustomAction:action-item="onActions"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="grouppaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="grouppagination"
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
    import * as api from './../../../config';
    import GroupCustomActions from './GroupCustomActions.vue'
    import GroupCustomFilterBar from './GroupCustomFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);

    Vue.component('group-custom-actions', GroupCustomActions);
//    Vue.component('group-custom-filter-bar', GroupCustomFilterBar);

    export default {
        computed: {
            url(){
                return api.currentGroupNodes;
            }
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'group-custom-filter-bar': GroupCustomFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        name: 'id',
                        title: '#',
                        titleClass: 'text-right',
                        dataClass: 'text-right'
                    },
                    {
                        title: 'Group Name',
                        name: 'name',
                        sortField: 'groups.name',
                    },
                    {
                        title: 'Role Name',
                        name: 'role.name',
                        sortField: 'roles.name',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                    },
                    {
                        name: '__component:group-custom-actions',
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
                    { field: 'name', sortField: 'groups.name', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onClickNew() {
                console.log('groupListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', data: null, index: ''},
                };
                this.$store.dispatch('setGroupShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.grouppagination.setPaginationData(paginationData);
                this.$refs.grouppaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.groupvuetable.changePage(page)
            },
            onActions (data) {
                console.log('groupListTable onActions', data);
                let payload = {
                    isShow: true,
                    data: data,
                };
                if (data.action === 'Delete')
                {
                    let swal = this.$swal;
                    let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this group!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        allowOutsideClick: false
                    }).then(function() {
                        me.$store.dispatch('deleteGroupRequest', data.data)
                            .then((response) => {
                                console.log('deleteGroupRequest fire events -> refreshGroupTable');
                                me.$events.fire('refreshGroupTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setGroupShowModal', payload);
            },
        },
        events: {
            'group-filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.groupvuetable.refresh() )
            },
            'group-filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.groupvuetable.refresh() )
            },
            'refreshGroupTable'() {
                console.log('events -> refreshGroupTable');
                Vue.nextTick( () => this.$refs.groupvuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.groupvuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.grouppagination.setPaginationData(this.$refs.groupvuetable.tablePagination)
                })
            },
            url() {
                console.log('watch +++++++ url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.groupvuetable.refresh()
                })
            }
        },
    }
</script>
<style>
    .pagination {
        margin: 0;
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
</style>