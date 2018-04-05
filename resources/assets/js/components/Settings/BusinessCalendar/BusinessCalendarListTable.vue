<template>
    <div>
        <business-calendar-crud-modal
                :isShow="isShowModal"
                :title="modalTitle"
                :dataItem="dataItem"
                @onCloseModal="onCloseModal">
        >
        </business-calendar-crud-modal>
        <div>
            <div class="pull-left">
                <business-calendar-filter-bar @onClickNew="onClickNew"></business-calendar-filter-bar>
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
        <vuetable ref="businessCalendarTable"
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
            <template slot="business-calendar-slot-actions" scope="props">
                <div>
                    <button  v-if="(props.rowData.type != 'PUBLIC_HOLIDAY')"
                            class="btn btn-warning btn-sm"
                            @click="updateBusinessCalendar(props.rowData)"
                            title="Update">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button
                            v-if="(props.rowData.type != 'PUBLIC_HOLIDAY')"
                            class="btn btn-danger btn-sm"
                            @click="deleteBusinessCalendar(props.rowData)"
                            title="Delete">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </div>
            </template>
        </vuetable>

        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="businessCalendarPaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="businessCalendarPagination"
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
    import BusinessCalendarFilterBar from './BusinessCalendarFilterBar.vue'
    import BusinessCalendarCrudModal from './BusinessCalendarCrudModal.vue'

    export default {
        computed: {
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'business-calendar-filter-bar': BusinessCalendarFilterBar,
            'business-calendar-crud-modal': BusinessCalendarCrudModal,
        },
        data () {
            return {
                url: '',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Location',
                        name: 'location.name',
                    },
                    {
                        title: 'Type',
                        name: 'type',
                    },
                    {
                        title: 'Year',
                        name: 'year',
                    },
                    {
                        title: 'Date',
                        name: 'value',
                        sortField: 'business_calendar_holidays.value',
                    },
                    {
                        title: 'Description',
                        name: 'description',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:business-calendar-slot-actions',
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
                    { sortField: 'business_calendar_holidays.value', direction: 'asc'}
                ],
                moreParams: {},
                isShowModal: false,
                dataItem: null,
                modalTitle: '',
            }
        },
        methods: {
            updateBusinessCalendar(dataItem) {
                console.log('BusinessCalendarListTable updateBusinessCalendar dataItem=', dataItem);
                this.modalTitle = 'Editing the date';
                this.dataItem = {action: 'Edit', data: dataItem};
                this.onOpenModal();
            },
            deleteBusinessCalendar(dataItem) {
                console.log('BusinessCalendarListTable deleteBusinessCalendar dataItem=', dataItem);
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this date!',
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
                    this.$store.dispatch('deleteBusinessCalendarDateRequest', dataItem)
                        .then((response) => {
                            console.log('deleteBusinessCalendarDateRequest fire events -> refreshBusinessCalendarTable');
                            this.$events.fire('refreshBusinessCalendarTable');
                        })
                        .catch((error) => {});
                }, (dismiss) => {
                });
            },
            onOpenModal() {
                console.log('BusinessCalendarListTable onOpenModal');
                this.isShowModal = true;
            },
            onCloseModal() {
                console.log('BusinessCalendarListTable onCloseModal');
                this.dataItem = null;
                this.modalTitle = '';
                this.isShowModal = false;
            },
            onClickNew() {
                console.log('BusinessCalendarListTable onClickNew');
                this.modalTitle = 'Adding a new date';
                this.dataItem = {action: 'Add', data: null};
                this.onOpenModal();
            },
            onPaginationData (paginationData) {
                this.$refs.businessCalendarPagination.setPaginationData(paginationData);
                this.$refs.businessCalendarPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.businessCalendarTable.changePage(page)
            },
        },
        events: {
            'business-calendar-filter-set' (filterData) {
                this.url = api.currentBusinessCalendarList;
                this.moreParams = {
                    filter: filterData
                };
                Vue.nextTick( () =>
                {
                    if (this.$refs.businessCalendarTable)
                        this.$refs.businessCalendarTable.refresh()
                });
            },
            'business-calendar-filter-reset' () {
                this.moreParams = {};
                this.url = api.currentBusinessCalendarList;
                Vue.nextTick( () =>
                {
                    if (this.$refs.businessCalendarTable)
                        this.$refs.businessCalendarTable.refresh()
                });
            },
            'refreshBusinessCalendarTable'() {
                console.log('events -> refreshBusinessCalendarTable');
                Vue.nextTick( () =>
                {
                    if (this.$refs.businessCalendarTable)
                        this.$refs.businessCalendarTable.refresh()
                } );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.businessCalendarTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.businessCalendarPagination.setPaginationData(this.$refs.businessCalendarTable.tablePagination)
                })
            },
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