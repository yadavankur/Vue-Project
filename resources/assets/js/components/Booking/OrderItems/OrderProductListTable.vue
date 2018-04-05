<template>
    <div>
        <div class="table-responsive">
            <div class="pull-left">
                <order-product-list-filter-bar></order-product-list-filter-bar>
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

        <vuetable ref="orderProductListVuetable"
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
            <vuetable-pagination-info ref="orderItemspaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="orderItemspagination"
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
    import OrderProductListFilterBar from './OrderProductListFilterBar.vue'

    export default {
        computed: {
//            url(){
//                this.moreParams = {
//                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
//                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: ''
//                };
//                return api.currentOrderItem;
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
            'order-product-list-filter-bar': OrderProductListFilterBar,
        },
        data () {
            return {
                url: '',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        name: '__sequence',
                        title: '#',
                        titleClass: 'text-right',
                        dataClass: 'text-right'
                    },
                    {
                        name: 'QTE_POS',
                        title: 'Item No',
                        titleClass: 'text-right hidden-xs',
                        dataClass: 'text-right hidden-xs'
                    },
                    {
                        title: 'Qty',
                        name: 'QUANTITY',
                        callback: 'getQty'
                    },
                    {
                        title: 'Product',
                        name: 'FRA_CODE',
                        sortField: 'FRA_CODE',
                    },
                    {
                        title: 'FINCOL_CODE',
                        name: 'FINCOL_CODE',
                        sortField: 'FINCOL_CODE',
                    },
                    {
                        title: 'Glass',
                        name: 'Glass',
                        sortField: 'Glass',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
                    },
                    {
                        title: 'KPA',
                        name: 'PRESSURE_CODE',
                        sortField: 'PRESSURE_CODE',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
                    },
                    {
                        title: 'PoM',
                        name: 'POM',
                        sortField: 'POM',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
                    },
                    {
                        title: 'Reveal',
                        name: 'BAG_CODE',
                        sortField: 'BAG_CODE',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
                    },
                    {
                        title: 'Height',
                        name: 'HEIGHT',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
                    },
                    {
                        title: 'Width',
                        name: 'WIDTH',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
                    },
                    {
                        title: 'Description',
                        name: 'CUST_REF',
                        titleClass: 'hidden-xs',
                        dataClass: 'hidden-xs'
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
                    { field: 'QTE_POS', sortField: 'QTE_POS', direction: 'asc'}
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
                this.$refs.orderItemspagination.setPaginationData(paginationData);
                this.$refs.orderItemspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.orderProductListVuetable.changePage(page)
            },
        },
        events: {
            'order-product-list-filter-set' (filterText) {
                this.url = api.currentOrderItem;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    filter: filterText
                };
                Vue.nextTick( () => this.$refs.orderProductListVuetable.refresh() )
            },
            'order-product-list-filter-reset' () {
                this.url = api.currentOrderItem;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                };
                Vue.nextTick( () => this.$refs.orderProductListVuetable.refresh() )
            },
            'refreshOrderProductListTable'() {
                console.log('events -> refreshOrderProductListTable');
                Vue.nextTick( () => this.$refs.orderProductListVuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderProductListVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderItemspagination.setPaginationData(this.$refs.orderProductListVuetable.tablePagination)
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