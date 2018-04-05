<template>
    <div>
        <div>
            <div class="pull-left">
                <associated-jobs-filter-bar></associated-jobs-filter-bar>
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

        <associated-jobs-vue-table ref="associatedJobsVueTable"
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
                              @vuetable:pagination-data="onPaginationData">
            <template slot="confirmation-slot-action" scope="props">
                <div>{{ props.rowData.confirmed_date ? (props.rowData.booking? props.rowData.booking.updated_by.name: '') : ''}}</div>
            </template>
        </associated-jobs-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="associatedJobspaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="associatedJobspagination"
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
    import AssociatedJobsFilterBar from './AssociatedJobsFilterBar.vue'
    import AssociatedJobsActions from './AssociatedJobsActions.vue'
    import moment from 'moment'

    Vue.component('associated-jobs-actions', AssociatedJobsActions);

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.tab.selectedOrder,
            }),
            url(){
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                return api.currentAssociatedJobsList;
            },
        },
        props: {
        },
        mounted() {
        },
        components: {
            'associated-jobs-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'associated-jobs-filter-bar': AssociatedJobsFilterBar,
        },
        data () {
            return {
                //url:'',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'SO NO',
                        name: 'jobNo',
                        //sortField: 'jobNo',
                    },
                    {
                        title: 'Description',
                        name: 'UDF5',
                        sortField: 'UDF5',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Original Delivery Date',
                        name: 'DELIVERY_DATE',
                        sortField: 'DELIVERY_DATE',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Agreed Delivery Date',
                        name: 'agreed_date',
                        sortField: 'booking_confirmation.agreed_date',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'BOOKED',
                        name: 'booked',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatCheckBox',
                    },
                    {
                        title: 'Confirmed Date',
                        name: 'confirmed_date',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Confirmed By',
                        name: '__slot:confirmation-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        name: '__component:associated-jobs-actions',
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
                    { field: 'V_V6_QUOTE.QUOTE_NUM_SUFF', sortField: 'V_V6_QUOTE.QUOTE_NUM_SUFF', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            formatCheckBox(value) {
                //return '<div align="center"><input type="checkbox"' + (parseInt(value)? 'checked': '')  + ' disabled></div>';
                let element = '';
                switch (parseInt(value))
                {
                    case 0:
                        element = '<span class="label label-warning" title="NO"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                    case 1:
                        element = '<span class="label label-success" title="YES"><span class="glyphicon glyphicon-ok-sign"></span></span>';
                        break;
                    default:
                        element = '<span class="label label-danger" title="NO"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                }
                return element;
            },
            onPaginationData (paginationData) {
                this.$refs.associatedJobspagination.setPaginationData(paginationData);
                this.$refs.associatedJobspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.associatedJobsVueTable.changePage(page)
            },
            onActions (data) {
                console.log('associatedJobsTable onActions', data);
                console.log('associatedJobsTable onActions data.data.id=', data.data.id);
                // delete the associated job
                if (data && data.action == 'Delete' && data.data.id)
                {
                    let payload = {
                        id: data.data.id
                    };
                    this.$store.dispatch('deleteAssociatedJobRequest', payload)
                        .then((response) => {
                            console.log('deleteAssociatedJobRequest fire events -> refreshAssociatedJobsListTable');
                            this.$events.fire('refreshAssociatedJobsListTable');
                        })
                        .catch((error) => {});
                }
                else if (data && data.action == 'View' && data.data.id)
                {
                    let payload = {
                        orderNo: data.data.UDF1,
                        location: data.data.QUOTE_NUM_PREF,
                    };
                    this.$emit('onSearch', payload);
                }
            },
        },
        events: {
            'associated-jobs-list-filter-set' (filterData) {
                console.log('associated-jobs-list-filter-setfilter Data=', filterData);
                //this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.associatedJobsVueTable)
                        this.$refs.associatedJobsVueTable.refresh();
                });
            },
            'associated-jobs-list-reset' () {
                console.log('associated-jobs-list-reset');
                //this.url = api.currentAssociatedJobsList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.associatedJobsVueTable)
                        this.$refs.associatedJobsVueTable.refresh()
                } )
            },
            'refreshAssociatedJobsListTable'() {
                console.log('events -> refreshAssociatedJobsListTable');
                Vue.nextTick( () => {
                    if (this.$refs.associatedJobsVueTable)
                        this.$refs.associatedJobsVueTable.refresh()
                } );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.associatedJobsVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.associatedJobspagination.setPaginationData(this.$refs.associatedJobsVueTable.tablePagination)
                })
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
    /*.form-control {*/
        /*height: 34px !important;*/
    /*}*/
    .filter-bar {
        padding: 10px;
    }
    label {
        margin-top: 10px !important;
    }
</style>
