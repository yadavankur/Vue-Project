<template>
    <div>
        <div class="table-responsive">
            <div class="pull-left">
                <order-bom-fill-list-filter-bar></order-bom-fill-list-filter-bar>
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

        <vuetable ref="orderBomFillListVuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  trackBy="QTE_POS"
                  :sort-order="sortOrder"
                  :multi-sort="true"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="orderBomFillspaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="orderBomFillspagination"
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
    import OrderItemBomFillListFilterBar from './OrderItemBomFillListFilterBar.vue'

    export default {
        computed: {
//            url(){
//                this.moreParams = {
//                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
//                    quoteVer: this.selectedOrder?  this.selectedOrder.QUOTE_VERS : '',
//                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
//                };
//                return api.currentOrderBomFill;
//            },
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.tab.selectedOrder,
            }),
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'order-bom-fill-list-filter-bar': OrderItemBomFillListFilterBar,
        },
        data () {
            return {
                url: '',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {

                        title: 'Item No',
                        name: 'QTE_POS',
                        titleClass: 'text-right',
                        dataClass: 'text-right'
                    },
                    {
                        title: 'Description',
                        name: 'DESCR',
                    },
                    {
                        title: 'Date Ordered',
                        name: 'DLDate',
                        sortField: 'V_OOPs_FILL_IMP.DLDate',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Qty',
                        name: 'FILL_COUNT',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },

                    {
                        title: 'Height',
                        name: 'FILL_HEIGHT',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Width',
                        name: 'FILL_WIDTH',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Ps',
                        name: 'FILL_CODE',
                        sortField: 'FILL_CODE',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
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
                    { field: 'QTE_POS', sortField: 'V_V6_QUOTE_ITEM.QTE_POS', direction: 'asc'}
                ],
                moreParams: {
                }
            }
        },
        methods: {
            getQty(value)
            {
                return parseInt(value);
            },
            onPaginationData (paginationData) {
                this.$refs.orderBomFillspagination.setPaginationData(paginationData);
                this.$refs.orderBomFillspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.orderBomFillListVuetable.changePage(page)
            },
        },
        events: {
            'order-bom-fill-list-filter-set' (filterData) {
                this.url = api.currentOrderBomFill;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    filter: filterData
                };
                Vue.nextTick( () => this.$refs.orderBomFillListVuetable.refresh() )
            },
            'order-bom-fill-list-filter-reset' () {
                this.url = api.currentOrderBomFill;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                };
                Vue.nextTick( () => this.$refs.orderBomFillListVuetable.refresh() )
            },
            'refreshOrderBomFillTable'() {
                console.log('events -> refreshOrderBomFillTable');
                Vue.nextTick( () => this.$refs.orderBomFillListVuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderBomFillListVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderBomFillspagination.setPaginationData(this.$refs.orderBomFillListVuetable.tablePagination)
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