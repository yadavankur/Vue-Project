<template>
    <div>
        <div>
            <cpm-service-group-crud-modal
                    @onCloseCrudModal="onCloseCrudModal"
                    :dataItem="dataItem"
                    :title="crudTitle"
                    :isShowModal="isShowModal">
            </cpm-service-group-crud-modal>
            <div class="pull-left">
                <cpm-service-group-custom-filter-bar
                        @onClickNew="onClickNew">
                </cpm-service-group-custom-filter-bar>
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

        <vuetable ref="cpmServiceGroupVuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  :sort-order="sortOrder"
                  :multi-sort="true"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        >
            <template slot="cpm-service-group-slot-action" scope="props">
                <div>
                    <button class="btn btn-warning btn-sm" @click="editServiceGroup(props.rowData)" title="Edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button class="btn btn-danger btn-sm" @click="deleteServiceGroup(props.rowData)" title="Delete">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </div>
            </template>
        </vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="cpmServiceGroupPaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="cpmServiceGroupPagination"
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
    import CpmServiceGroupCustomFilterBar from './CpmServiceGroupCustomFilterBar.vue'
    import CpmServiceGroupCrudModal from './CpmServiceGroupCrudModal.vue'

    export default {
        computed: {
            url(){
                return api.currentCpmServiceGroupNodes;
            }
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cpm-service-group-custom-filter-bar': CpmServiceGroupCustomFilterBar,
            'cpm-service-group-crud-modal': CpmServiceGroupCrudModal,
        },
        data () {
            return {
                isShowModal: false,
                dataItem: null,
                crudTitle: '',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Location Name',
                        name: 'service.location.name',
                        sortField: 'locations.name',
                    },
                    {
                        title: 'Service Name',
                        name: 'service.name',
                        sortField: 'cpm_services.name',
                    },
                    {
                        title: 'Service Group Name',
                        name: 'name',
                        sortField: 'cpm_service_groups.name',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:cpm-service-group-slot-action',
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
                    { field: 'name', sortField: 'name', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            editServiceGroup(rowData) {
                console.log('editServiceGroup rowData=', rowData);
                let payload = {
                    data: {action: 'Edit', data: rowData},
                };
                this.dataItem = payload;
                this.crudTitle = 'Editing the Service Group';
                this.onOpenCrudModal();
            },
            deleteServiceGroup(rowData) {
                console.log('deleteServiceGroup rowData=', rowData);
                let payload = {
                    type: 'Delete',
                    id: rowData.id,
                    name: rowData.name,
                    comment: rowData.comment,
                    location: {name: rowData.service.location.name, id:rowData.service.location.id},
                    service: {name:rowData.service.name, id:rowData.service.id},
                };
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this service group!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    allowOutsideClick: false
                }).then(() => {
                    this.$store.dispatch('deleteCpmServiceGroupRequest', payload)
                        .then((response) => {
                            console.log('deleteCpmServiceGroupRequest fire events -> refreshCpmServiceGroupTable');
                            if (this.$refs.cpmServiceGroupVuetable)
                                this.$refs.cpmServiceGroupVuetable.refresh()
                        })
                        .catch((error) => {});
                    }, (dismiss) => {
                });
            },
            onOpenCrudModal() {
                console.log('onOpenCrudModal');
                this.isShowModal = true;
            },
            onCloseCrudModal() {
                console.log('onCloseCrudModal');
                this.isShowModal = false;
                this.dataItem = null;
            },
            onClickNew() {
                console.log('cpmServiceGroupListTable onClickNew');
                let payload = {
                    data: {action: 'Add', data: null},
                };
                this.dataItem = payload;
                this.crudTitle = 'Adding a Service Group';
                this.onOpenCrudModal();
            },
            onPaginationData (paginationData) {
                this.$refs.cpmServiceGroupPagination.setPaginationData(paginationData);
                this.$refs.cpmServiceGroupPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.cpmServiceGroupVuetable.changePage(page)
            },
        },
        events: {
            'cpm-service-group-filter-set' (filterData) {
                this.moreParams = {
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.cpmServiceGroupVuetable)
                        this.$refs.cpmServiceGroupVuetable.refresh()
                } )
            },
            'cpm-service-group-filter-reset' () {
                this.moreParams = {};
                Vue.nextTick( () => {
                    if (this.$refs.cpmServiceGroupVuetable)
                        this.$refs.cpmServiceGroupVuetable.refresh()
                } )
            },
            'refreshCpmServiceGroupTable'() {
                console.log('events -> refreshCpmServiceGroupTable');
                Vue.nextTick( () => {
                    if (this.$refs.cpmServiceGroupVuetable)
                        this.$refs.cpmServiceGroupVuetable.refresh()
                } );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cpmServiceGroupVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.cpmServiceGroupPagination.setPaginationData(this.$refs.cpmServiceGroupVuetable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>