<template>
    <div>
        <div>
            <div class="pull-left">
                <device-type-custom-filter-bar @onClickNew="onClickNew"></device-type-custom-filter-bar>
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

        <vuetable ref="deviceTypevuetable"
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
            <vuetable-pagination-info ref="deviceTypepaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="deviceTypepagination"
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
    import DeviceTypeCustomActions from './DeviceTypeCustomActions.vue'
    import DeviceTypeCustomFilterBar from './DeviceTypeCustomFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);

    Vue.component('device-type-custom-actions', DeviceTypeCustomActions);
//    Vue.component('device-type-custom-filter-bar', DeviceTypeCustomFilterBar);

    export default {
        computed: {
            url(){
                return api.currentDeviceTypeNodes;
            }
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'device-type-custom-filter-bar': DeviceTypeCustomFilterBar,
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
                        title: 'DeviceType Name',
                        name: 'name',
                        sortField: 'name',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                    },
                    {
                        name: '__component:device-type-custom-actions',
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
                    { field: 'name', sortField: 'name', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onClickNew() {
                console.log('deviceTypeListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', data: null, index: ''},
                };
                this.$store.dispatch('setDeviceTypeShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.deviceTypepagination.setPaginationData(paginationData);
                this.$refs.deviceTypepaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.deviceTypevuetable.changePage(page)
            },
            onActions (data) {
                console.log('deviceTypeListTable onActions', data);
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
                        text: 'You will not be able to recover this device type!',
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
                        me.$store.dispatch('deleteDeviceTypeRequest', data.data)
                            .then((response) => {
                                console.log('deleteDeviceTypeRequest fire events -> refreshDeviceTypeTable');
                                me.$events.fire('refreshDeviceTypeTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setDeviceTypeShowModal', payload);
            },
        },
        events: {
            'device-type-filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.deviceTypevuetable.refresh() )
            },
            'device-type-filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.deviceTypevuetable.refresh() )
            },
            'refreshDeviceTypeTable'() {
                console.log('events -> refreshDeviceTypeTable');
                Vue.nextTick( () => this.$refs.deviceTypevuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.deviceTypevuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.deviceTypepagination.setPaginationData(this.$refs.deviceTypevuetable.tablePagination)
                })
            },
            url() {
                console.log('watch +++++++ url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.deviceTypevuetable.refresh()
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