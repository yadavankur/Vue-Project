<template>
    <div>
        <cs-adhoc-activity-modal
                :isShowAdhocActivity="isShowAdhocActivity"
                :order="selectedOrder? selectedOrder: null"
                :itemData="itemData"
                @onCloseAdhocActivityModal="onCloseAdhocActivityModal"
                @onRefreshOrderMatrixTable="onRefreshOrderMatrixTable"
        >
        </cs-adhoc-activity-modal>
        <cs-cpm-simulator-main-modal
                :quoteId="selectedOrder?selectedOrder.quote_id: ''"
                :locationId="selectedOrder?selectedOrder.location_id: ''"
                :orderId="selectedOrder?selectedOrder.order_id: ''"
                :serviceId="serviceId"
                :isShowCPMSimulator="isShowCPMSimulator"
                @onCloseCPMSimulatorModal="onCloseCPMSimulatorModal"
        >
        </cs-cpm-simulator-main-modal>
        <cs-activity-info-modal
                :isShowActivityInfo="isShowActivityInfo"
                :order="selectedOrder? selectedOrder: null"
                :actionTitle="actionTitle"
                :actionType="actionType"
                :itemData="itemData"
                @onCloseActivityInfoModal="onCloseActivityInfoModal"
                @onRefreshOrderMatrixTable="onRefreshOrderMatrixTable"
        >
        </cs-activity-info-modal>
        <div class="row-content">
            <div class="pull-left">
                <cs-order-activity-filter-bar
                    :cascadeServiceGroupOptions="cascadeServiceGroupOptions"
                    @onOpenCPMSimulatorModal="onOpenCPMSimulatorModal"
                >
                </cs-order-activity-filter-bar>
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
            <cs-order-activity-vue-table ref="csOrderActivityVueTable"
                                                       :api-url="url"
                                                       :fields="fields"
                                                       :per-page="perPage"
                                                       :pagination-path="paginationPath"
                                                       :css="css.table"
                                                       :row-class="onRowClass"
                                                       :sort-order="sortOrder"
                                                       trackBy="activity_id"
                                                       :multi-sort="true"
                                                       :append-params="moreParams"
                                                       @vuetable:pagination-data="onPaginationData">
                <template slot="order-activity-slot-action" scope="props">
                    <div>
                        <button class="btn btn-warning btn-xs" @click="addAdhocActivity(props.rowData)" title="Add ad hoc activity">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        <button class="btn btn-primary btn-xs" @click="updateActivityDetail(props.rowData)" title="Update">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button v-if="(props.rowData.status_id == 2) || (props.rowData.status_id == 3)"
                                class="btn btn-danger btn-xs" @click="showStatusDetail(props.rowData)"
                                :title="getStatusDetail(props.rowData.status_id)">
                            <span class="glyphicon glyphicon-zoom-in"></span>
                        </button>
                    </div>
                </template>
            </cs-order-activity-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="csOrderActivitypaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="csOrderActivitypagination"
                                     :css="css.pagination"
                                     :icons="css.icons"
                                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>
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
    import CSOrderActivityFilterBar from './OrderActivityFilterBar.vue'
    import CSActivityInfoModal from './ActivityInfo/ActivityInfoModal.vue'
    import CSCPMSimulatorMainModal from '../CPMSimulator/CPMSimulatorMain.vue'
    import CSAdhocActivityModal from './AdhocActivity/AdhocActivityModal.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.customerService.selectedOrder,
            }),
            location()
            {
                return this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: '';
            },
        },
        props: {
        },
        mounted() {
        },
        created() {
            console.log('OrderActivityListTable -> created');
            this.getCascadeServiceGroupOptionsOfLocation();
        },
        components: {
            'cs-order-activity-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'cs-order-activity-filter-bar': CSOrderActivityFilterBar,
            'cs-activity-info-modal': CSActivityInfoModal,
            'cs-cpm-simulator-main-modal': CSCPMSimulatorMainModal,
            'cs-adhoc-activity-modal': CSAdhocActivityModal,
        },
        data () {
            return {
                serviceId: '',
                actionType: '',
                actionTitle: '',
                isShowActivityInfo: false,
                isShowCPMSimulator: false,
                isShowAdhocActivity: false,
                itemData: null,
                url:'',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Service',
                        name: 'service.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Activity',
                        name: 'activity.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Duration (Day)',
                        name: 'duration',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Original Delivery Date',
                        name: 'end_date',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Current Delivery Date',
                        name: 'delivery_date',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Status',
                        name: 'status_id',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatCheckBox',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:order-activity-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
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
                    { field: 'activity_id', sortField: 'cpm_order_matrixes.start_date', direction: 'asc'}
                ],
                moreParams: {},
                cascadeServiceGroupOptions: [],
            }
        },
        methods: {

            addAdhocActivity(dataItem) {
                console.log('addAdhocActivity. dataItem=', dataItem);
                this.itemData = dataItem;
                this.onOpenAdhocActivityModal();
            },
            updateActivityDetail(dataItem) {
                console.log('updateActivityDetail. dataItem=', dataItem);
                this.actionType = 'update';
                this.actionTitle = 'Updating Activity Information';
                this.itemData = dataItem;
                this.onOpenActivityInfoModal();
            },
            showStatusDetail(dataItem) {
                console.log('showStatusDetail. dataItem=', dataItem);
                if (dataItem.status_id == 2)
                {
                    this.actionTitle = 'Delay Detail';
                }
                else if (dataItem.status_id == 3)
                {
                    this.actionTitle = 'Deferral Detail';
                }
                this.actionType = 'view';
                this.itemData = dataItem;
                this.onOpenActivityInfoModal();
            },
            onRowClass(dataItem, index) {
                console.log('onRowClass. dataItem=', dataItem);
                console.log('onRowClass. index=', index);
                return;
                let startDate = this.formatDate(dataItem.start_date, 'YYYY-MM-DD');
                let endDate = this.formatDate(dataItem.end_date, 'YYYY-MM-DD');
                let current = this.formatDate(this.getCurrentDatetime(), 'YYYY-MM-DD');
                console.log('onRowClass. startDate=', startDate);
                console.log('onRowClass. current=', current);
                console.log('onRowClass. endDate=', endDate);
                let predecessor_status_id = parseInt(dataItem.predecessor_status_id?dataItem.predecessor_status_id:0);
                let status_id = parseInt(dataItem.status_id);
                console.log('onRowClass. predecessor_status_id=', predecessor_status_id);
                console.log('onRowClass. status_id=', status_id);
                if (status_id == 2)
                {
                    return  'activity-delay';
                }
                else if (status_id == 3)
                {
                    return 'activity-delay';
                }
                else if (status_id == 4)
                {
                    return 'activity-on-time';
                }
                else if (status_id == 5)
                {
                    return 'activity-completed';
                }
                else if (predecessor_status_id == 0)
                {
                    console.log('onRowClass. predecessor_status_id=', predecessor_status_id);
                    // no predecessor means it is the first activity
                    if (this.dateCompare(current, startDate) && this.dateCompare(endDate, current))
                    {
                        console.log('onRowClass. predecessor_status_id=0 activity-ongoing');
                        return 'activity-ongoing';
                    }
                    else if (this.dateCompare(current, endDate))
                    {
                        console.log('onRowClass. predecessor_status_id=0 activity-delay');
                        return 'activity-delay';
                    }
                    else
                    {
                        console.log('onRowClass. predecessor_status_id=0 activity-startable');
                        // actually even thought current < startDate it still can be started
                        return 'activity-startable';
                    }
                }
                else if ((predecessor_status_id > 0) && (predecessor_status_id < 5))
                {
                    console.log('onRowClass. < 5');
                    // has predecessors and are not all completed
                    if (this.dateCompare(current, startDate) && this.dateCompare(endDate, current))
                    {
                        console.log('onRowClass. activity-delay-warning');
                        // but it has already over
                        return 'activity-delay-warning';
                    }
                    else if (this.dateCompare(current, endDate))
                    {
                        console.log('onRowClass. activity-delay');
                        // delay
                        return 'activity-delay';
                    }
                    console.log('onRowClass. nothing');
                }
                else if (predecessor_status_id == 5)
                {
                    // has predecessors and are all completed
                    if (this.dateCompare(current, endDate))
                    {
                        // but it has already over
                        return 'activity-delay';
                    }
                    else if (this.dateCompare(current, startDate) && this.dateCompare(endDate, current))
                    {
                        return 'activity-ongoing';
                    }
                    else
                    {
                        return 'activity-startable';
                    }
                }
                return 'activity-new';
            },
            getStatusDetail(status_id) {
                console.log('getStatusDetail. status_id=', status_id);
                if (status_id == 2)
                {
                    return 'Delay';
                } else if (status_id == 3)
                {
                    return 'Deferral';
                }
                return 'Test';
            },
            formatCheckBox(value) {
                console.log('OrderActivityListTable -> formatCheckBox');
                //return '<div align="center"><input type="checkbox"' + (parseInt(value) == 5 ? 'checked': '')  + ' disabled></div>';
                let element = '';
                switch (parseInt(value))
                {
                    case 1:
                        element = '<span class="label label-primary">NEW</span>';
                        break;
                    case 2:
                        element = '<span class="label label-danger">DELAY</span>';
                        break;
                    case 3:
                        element = '<span class="label label-warning">DEFERRAL</span>';
                        break;
                    case 4:
                        element = '<span class="label label-info">ON TIME</span>';
                        break;
                    case 5:
                        element = '<span class="label label-success">COMPLETED</span>';
                        break;
                }
                return element;
            },
            onPaginationData (paginationData) {
                console.log('OrderActivityListTable -> onPaginationData');
                this.$refs.csOrderActivitypagination.setPaginationData(paginationData);
                this.$refs.csOrderActivitypaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('OrderActivityListTable -> onChangePage');
                this.$refs.csOrderActivityVueTable.changePage(page)
            },
            onOpenCPMSimulatorModal(serviceId) {
                console.log('onOpenCPMSimulatorModal.');
                this.serviceId = serviceId;
                this.isShowCPMSimulator = true;
            },
            onCloseCPMSimulatorModal() {
                console.log('onCloseCPMSimulatorModal.');
                this.serviceId = '';
                this.isShowCPMSimulator = false;
            },
            onOpenAdhocActivityModal() {
                console.log('onOpenAdhocActivityModal.');
                this.isShowAdhocActivity = true;
            },
            onCloseAdhocActivityModal() {
                console.log('onCloseAdhocActivityModal.');
                this.isShowAdhocActivity = false;
            },
            onOpenActivityInfoModal() {
                console.log('onOpenActivityInfoModal.');
                this.isShowActivityInfo = true;
            },
            onCloseActivityInfoModal() {
                console.log('onCloseActivityInfoModal.');
                this.isShowActivityInfo = false;
            },
            onRefreshOrderMatrixTable() {
                console.log('onRefreshOrderMatrixTable.');
                if (this.$refs.csOrderActivityVueTable)
                    this.$refs.csOrderActivityVueTable.refresh();
            }
        },
        events: {
            'cs-order-activity-list-filter-set' (filterData) {
                console.log('cs-order-activity-list-filter-set Data=', filterData);
                this.url = api.currentOrderActivityList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: '',
                    serviceId: filterData.service.id,
                    serviceGroupId: filterData.service_group.id,
                    filter: filterData.filterText,
                };
                Vue.nextTick( () => {
                    if (this.$refs.csOrderActivityVueTable)
                        this.$refs.csOrderActivityVueTable.refresh();
                });
            },
            'cs-order-activity-list-filter-reset' () {
                console.log('cs-order-activity-list-filter-reset');
                this.url = api.currentOrderActivityList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.order_id: '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: '',
                    serviceId: '',
                    serviceGroupId: '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.csOrderActivityVueTable)
                        this.$refs.csOrderActivityVueTable.refresh()
                });
            },
            'refreshCSOrderActivityListTable'() {
                console.log('events -> refreshCSOrderActivityListTable');
                Vue.nextTick( () => {
                    if (this.$refs.csOrderActivityVueTable)
                        this.$refs.csOrderActivityVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('OrderActivityListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.csOrderActivityVueTable)
                        this.$refs.csOrderActivityVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('OrderActivityListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.csOrderActivitypagination.setPaginationData(this.$refs.csOrderActivityVueTable.tablePagination)
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
