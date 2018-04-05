<template>
    <div>
        <div>
            <div class="pull-left">
                <cpm-master-custom-filter-bar :cascadeServiceOptions="cascadeServiceOptions" @onClickNew="onClickNew"></cpm-master-custom-filter-bar>
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

        <vuetable ref="cpmMastervuetable"
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
            <vuetable-pagination-info ref="cpmMasterpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="cpmMasterpagination"
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
    import * as api from './../../../../config';
    import CpmMasterCustomActions from './CpmMasterCustomActions.vue'
    import CpmMasterCustomFilterBar from './CpmMasterCustomFilterBar.vue'

    Vue.component('cpm-master-custom-actions', CpmMasterCustomActions);

    export default {
        computed: {
            url(){
                return api.currentCpmMasterNodes;
            }
        },
        props: {
            cascadeServiceOptions: {
                type: Array,
                default: [],
            }
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cpm-master-custom-filter-bar': CpmMasterCustomFilterBar,
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
                        title: 'Location/Service Name',
                        name: 'service',
                        callback: 'getLocationServiceName',
                    },
                    {
                        title: 'Activity Name',
                        name: 'activity.name',
                        sortField: 'cpm_activities.name',
                    },
                    {
                        title: 'Duration',
                        name: 'activity.duration',
                    },
                    {
                        title: 'Predecessor Activity Name',
                        name: 'predecessor.name',
                    },
                    {
                        title: 'Successor Activity Name',
                        name: 'successor.name',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                    },
                    {
                        name: '__component:cpm-master-custom-actions',
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
                    { field: 'id', sortField: 'cpm_masters.id', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            getLocationServiceName(service)
            {
                console.log('getLocationServiceName service=', service);
                return service.location.name + '/' + service.name;
            },
            onClickNew() {
                console.log('cpmMasterListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', data: null, index: ''},
                };
                this.$store.dispatch('setCpmMasterShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.cpmMasterpagination.setPaginationData(paginationData);
                this.$refs.cpmMasterpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.cpmMastervuetable.changePage(page)
            },
            onActions (data) {
                console.log('cpmMasterListTable onActions', data);
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
                        text: 'You will not be able to recover this activity!',
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
                        me.$store.dispatch('deleteCpmMasterRequest', data.data)
                            .then((response) => {
                                console.log('deleteCpmMasterRequest fire events -> refreshCpmMasterTable');
                                me.$events.fire('refreshCpmMasterTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setCpmMasterShowModal', payload);
            },
        },
        events: {
            'cpm-master-filter-set' (filterData) {
                this.moreParams = {
                    service: filterData.service,
                    filter: filterData.filterText
                }
                Vue.nextTick( () => this.$refs.cpmMastervuetable.refresh() )
            },
            'cpm-master-filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.cpmMastervuetable.refresh() )
            },
            'refreshCpmMasterTable' () {
                console.log('events -> refreshCpmMasterTable');
                Vue.nextTick( () => this.$refs.cpmMastervuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cpmMastervuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cpmMasterpagination.setPaginationData(this.$refs.cpmMastervuetable.tablePagination)
                })
            },
            url() {
                console.log('watch +++++++ url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.cpmMastervuetable.refresh()
                })
            }
        },
    }
</script>
<style scoped>
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