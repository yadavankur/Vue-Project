<template>
    <div>
        <div class="table-responsive">
            <div class="pull-left">
                <order-bom-comp-list-filter-bar></order-bom-comp-list-filter-bar>
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

        <vuetable ref="orderBomCompListVuetable"
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
            <vuetable-pagination-info ref="orderBomCompspaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="orderBomCompspagination"
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
    import OrderItemBomCompListFilterBar from './OrderItemComponentFilterBar.vue'

    export default {
        computed: {
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
            'order-bom-comp-list-filter-bar': OrderItemBomCompListFilterBar,
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
                        title: 'Part Code',
                        name: 'PART_CODE',
                        sortField: 'PART_CODE',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Qty',
                        name: 'COMP_QTY',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },

                    {
                        title: 'Unit',
                        name: 'UNIT',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Fin Col Code',
                        name: 'FINCOL_CODE',
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
                this.$refs.orderBomCompspagination.setPaginationData(paginationData);
                this.$refs.orderBomCompspaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.orderBomCompListVuetable.changePage(page)
            },
        },
        events: {
            'order-bom-comp-list-filter-set' (filterData) {
                this.url = api.currentOrderBomComp;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                    filter: filterData
                };
                Vue.nextTick( () => this.$refs.orderBomCompListVuetable.refresh() )
            },
            'order-bom-comp-list-filter-reset' () {
                this.url = api.currentOrderBomComp;
                this.moreParams = {
                    quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID: '',
                    quoteVer: this.selectedOrder? this.selectedOrder.QUOTE_VERS: '',
                    orderId: this.selectedOrder? this.selectedOrder.UDF1: '',
                };
                Vue.nextTick( () => this.$refs.orderBomCompListVuetable.refresh() )
            },
            'refreshOrderBomCompTable'() {
                console.log('events -> refreshOrderBomCompTable');
                Vue.nextTick( () => this.$refs.orderBomCompListVuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderBomCompListVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.orderBomCompspagination.setPaginationData(this.$refs.orderBomCompListVuetable.tablePagination)
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