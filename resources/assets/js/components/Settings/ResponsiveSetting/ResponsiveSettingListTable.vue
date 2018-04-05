<template>
    <div>
        <div>
            <div class="pull-left">
                <responsive-setting-custom-filter-bar @onClickNew="onClickNew"></responsive-setting-custom-filter-bar>
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

        <vuetable ref="responsiveSettingvuetable"
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
            <vuetable-pagination-info ref="responsiveSettingpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="responsiveSettingpagination"
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
    import ResponsiveSettingCustomActions from './ResponsiveSettingCustomActions.vue'
    import ResponsiveSettingCustomFilterBar from './ResponsiveSettingCustomFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);

    Vue.component('responsive-setting-custom-actions', ResponsiveSettingCustomActions);
    //Vue.component('responsive-setting-custom-filter-bar', ResponsiveSettingCustomFilterBar);

    export default {
        computed: {
            url(){
                return api.currentResponsiveSettingNodes;
            }
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'responsive-setting-custom-filter-bar': ResponsiveSettingCustomFilterBar,
        },
        data () {
            return {
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        name: 'id',
                        title: '#',
                        titleClass: 'text-right',
                        dataClass: 'text-right'
                    },
                    {
                        title: 'Device Type Name',
                        name: 'device_type.name',
                        sortField: 'device_types.name',
                    },
                    {
                        title: 'Component Name',
                        name: 'component.name',
                        sortField: 'components.name',
                    },
                    {
                        title: 'Column Name',
                        name: 'column_name',
                        sortField: 'responsive_settings.column_name',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                    },
                    {
                        name: '__component:responsive-setting-custom-actions',
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
                    { field: 'column_name', sortField: 'responsive_settings.column_name', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onClickNew() {
                console.log('responsiveSettingListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', data: null, index: ''},
                };
                this.$store.dispatch('setResponsiveSettingShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.responsiveSettingpagination.setPaginationData(paginationData);
                this.$refs.responsiveSettingpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.responsiveSettingvuetable.changePage(page)
            },
            onActions (data) {
                console.log('responsiveSettingListTable onActions', data);
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
                        text: 'You will not be able to recover this responsive setting!',
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
                        me.$store.dispatch('deleteResponsiveSettingRequest', data.data)
                            .then((response) => {
                                console.log('deleteResponsiveSettingRequest fire events -> refreshResponsiveSettingTable');
                                me.$events.fire('refreshResponsiveSettingTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setResponsiveSettingShowModal', payload);
            },
        },
        events: {
            'responsive-setting-filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.responsiveSettingvuetable.refresh() )
            },
            'responsive-setting-filter-reset' () {
                this.moreParams = {};
                Vue.nextTick( () => this.$refs.responsiveSettingvuetable.refresh() )
            },
            'refreshResponsiveSettingTable'() {
                console.log('events -> refreshResponsiveSettingTable');
                Vue.nextTick( () => this.$refs.responsiveSettingvuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.responsiveSettingvuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.responsiveSettingpagination.setPaginationData(this.$refs.responsiveSettingvuetable.tablePagination)
                })
            },
            url() {
                console.log('watch +++++++ url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.responsiveSettingvuetable.refresh()
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
    .filter-bar {
        padding: 10px;
    }
</style>