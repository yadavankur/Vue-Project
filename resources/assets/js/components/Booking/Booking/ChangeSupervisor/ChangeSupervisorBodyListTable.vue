<template>
    <div>
        <div>
            <div class="pull-left">
                <change-supervisor-filter-bar @newSupervisor="newSupervisor">
                </change-supervisor-filter-bar>
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
        <change-supervisor-vue-table ref="supervisorVueTable"
                                        :api-url="url"
                                        :fields="fields"
                                        :per-page="perPage"
                                        :pagination-path="paginationPath"
                                        :css="css.table"
                                        :sort-order="sortOrder"
                                        trackBy="CONTACT_ID"
                                        :multi-sort="true"
                                        :append-params="moreParams"
                                        @vuetable:pagination-data="onPaginationData">
            <template slot="supervisor-slot-action" scope="props">
                <div>
                    <button class="btn btn-danger btn-xs" @click="selectSupervisor(props.rowData)" title="Select">
                        <span class="glyphicon glyphicon-ok-sign"> Select</span>
                    </button>
                </div>
            </template>
        </change-supervisor-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="supervisorpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="supervisorpagination"
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
    import ChangeSupervisorBodyFilterBar from './ChangeSupervisorBodyFilterBar.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            url(){
                this.moreParams = {
                    customerId: this.order? this.order.CUST_ID : '',
                };
                return api.currentCustomerContactList;
            },
        },
        props: {
            order : null,
        },
        mounted() {
        },
        components: {
            'change-supervisor-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'change-supervisor-filter-bar': ChangeSupervisorBodyFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Contact Name',
                        name: 'CONTACT_NAME',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'V_V6_CUSTOMER_CONTACTS.CONTACT_NAME',
                    },
                    {
                        title: 'Email Address',
                        name: 'EMAIL_ADDR',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Phone',
                        name: 'PHONE',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Mobile',
                        name: 'MOBILE',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:supervisor-slot-action',
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
                    { field: 'CONTACT_NAME', sortField: 'V_V6_CUSTOMER_CONTACTS.CONTACT_ID', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            selectSupervisor(dataItem) {
                console.log('selectSupervisor dataItem=', dataItem);
                // change supervisor
                this.$emit('selectSupervisor', dataItem);
            },
            newSupervisor() {
                console.log('ChangeSupervisorBodyList -> newSupervisor');
                this.$emit('newSupervisor');
            },
            onPaginationData (paginationData) {
                this.$refs.supervisorpagination.setPaginationData(paginationData);
                this.$refs.supervisorpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.supervisorVueTable.changePage(page)
            },
        },
        events: {
            'change-supervisor-list-filter-set' (filterData) {
                console.log('change-supervisor-list-filter-set filter Data=', filterData);
                this.moreParams = {
                    customerId: this.order? this.order.CUST_ID : '',
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.supervisorVueTable)
                        this.$refs.supervisorVueTable.refresh();
                });
            },
            'change-supervisor-list-filter-reset' () {
                console.log('change-supervisor-list-filter-reset');
                this.moreParams = {
                    customerId: this.order? this.order.CUST_ID : '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.supervisorVueTable)
                        this.$refs.supervisorVueTable.refresh();
                });
            },
            'refreshSupervisorTable'() {
                console.log('events -> refreshSupervisorTable');
                Vue.nextTick( () => {
                    if (this.$refs.supervisorVueTable)
                        this.$refs.supervisorVueTable.refresh();
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.supervisorVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.supervisorpagination.setPaginationData(this.$refs.supervisorVueTable.tablePagination)
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
    .filter-bar {
        padding: 10px;
    }
    label {
        margin-top: 10px !important;
    }
</style>
