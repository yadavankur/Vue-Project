<template>
    <div>
        <div class="table-responsive">
            <div class="pull-left">
                <order-finish-list-filter-bar></order-finish-list-filter-bar>
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

        <vuetable ref="orderFinishListVuetable"
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
            <vuetable-pagination-info ref="orderFinishpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="orderFinishpagination"
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
    import OrderItemFinishListFilterBar from './OrderItemFinishListFilterBar.vue'

    export default {
        computed: {
//            url(){
//                this.moreParams = {
//                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
//                    quoteVer: this.selectedOrder?  this.selectedOrder.QUOTE_VERS : '',
//                    orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
//                };
//                return api.currentOrderBomFinish;
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
            'order-finish-list-filter-bar': OrderItemFinishListFilterBar,
        },
        data () {
            return {
                url: '',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        name: 'QTE_POS',
                        title: 'Item No',
                        titleClass: 'text-right',
                        dataClass: 'text-right'
                    },
                    {
                        title: 'Finish',
                        name: 'FINCOL_CODE',
                        sortField: 'V_V6_BOM_EXTN.FINCOL_CODE',
                    },
                    {
                        title: 'Qty',
                        name: 'PIECE_COUNT',
                    },
                    {
                        title: 'Length',
                        name: 'PIECE_LENGTH',
                    },
                    {
                        title: 'Description',
                        name: 'DESCR',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Col-len',
                        name: 'EXTN_REF',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered table-striped table-hover table-responsive',
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
                },
                spinShow: false,

            }
        },
        methods: {
            getQty(value)
            {
                return parseInt(value);
            },
            onPaginationData (paginationData) {
                this.$refs.orderFinishpagination.setPaginationData(paginationData);
                this.$refs.orderFinishpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.orderFinishListVuetable.changePage(page);
            },
        },
        events: {
            'order-finish-list-filter-set' (filterData) {
                this.url = api.currentOrderBomFinish;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    filter: filterData
                };
                Vue.nextTick( () => this.$refs.orderFinishListVuetable.refresh() );
            },
            'order-finish-list-filter-reset' () {
                this.url = api.currentOrderBomFinish;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                };
                Vue.nextTick( () => this.$refs.orderFinishListVuetable.refresh() )
            },
            'refreshOrderFinishTable'() {
                console.log('events -> refreshOrderFinishTable');
                Vue.nextTick( () => this.$refs.orderFinishListVuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderFinishListVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderFinishpagination.setPaginationData(this.$refs.orderFinishListVuetable.tablePagination)
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