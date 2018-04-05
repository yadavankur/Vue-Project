<template>
    <div>
        <div>
            <div class="pull-left">
                <customer-tier-filter-bar @onClickNew="onClickNew"></customer-tier-filter-bar>
            </div>
            <div class="pull-right">
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

        <vuetable ref="customerTiervuetable"
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
            <vuetable-pagination-info ref="customerTierpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="customerTierpagination"
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
    import CustomerTierCustomActions from './CustomerTierCustomActions.vue'
    import CustomerTierCustomFilterBar from './CustomerTierCustomFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);
//
    Vue.component('customer-tier-actions', CustomerTierCustomActions);
//    Vue.component('customer-tier-filter-bar', CustomerTierCustomFilterBar);

    export default {
        computed: {
            url(){
                return api.currentCustomerTierNodes;
            }
        },
        props: {
        },
        components: {
            'customer-tier-filter-bar': CustomerTierCustomFilterBar,
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Customer Name',
                        name: 'CUST_NAME',
                        sortField: 'V_V6_CUSTOMER.CUST_NAME',
                    },
                    {
                        title: 'Customer Code',
                        name: 'CUST_CODE',
                        sortField: 'V_V6_CUSTOMER.CUST_CODE',
                    },
                    {
                        title: 'Customer Type Code',
                        name: 'CLASSIF_CODE',
                        sortField: 'V_V6_CUSTOMER.CLASSIF_CODE',
                    },
                    {
                        title: 'Tier Name',
                        name: 'name',
                        sortField: 'customer_tier_levels.name',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                    },
                    {
                        name: '__component:customer-tier-actions',
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
                    { field: 'CUST_NAME', sortField: 'V_V6_CUSTOMER.CUST_NAME', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onClickNew() {
                console.log('CustomerTierListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', data: null, index: ''},
                };
                this.$store.dispatch('setCustomerTierShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.customerTierpagination.setPaginationData(paginationData);
                this.$refs.customerTierpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.customerTiervuetable.changePage(page)
            },
            onActions (data) {
                console.log('CustomerTierListTable onActions', data);
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
                        text: 'You will not be able to recover this customer tier!',
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
                        me.$store.dispatch('deleteCustomerTierRequest', data.data)
                            .then((response) => {
                                console.log('deleteCustomerTierRequest fire events -> refreshCustomerTierTable');
                                me.$events.fire('refreshCustomerTierTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setCustomerTierShowModal', payload);
            },
        },
        events: {
            'customer-tier-filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.customerTiervuetable.refresh() )
            },
            'customer-tier-filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.customerTiervuetable.refresh() )
            },
            'refreshCustomerTierTable'() {
                console.log('events -> refreshCustomerTierTable');
                Vue.nextTick( () => this.$refs.customerTiervuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.customerTiervuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.customerTierpagination.setPaginationData(this.$refs.customerTiervuetable.tablePagination)
                })
            },
            url() {
                console.log('watch url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.customerTiervuetable.refresh()
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
</style>