<template>
    <div>
        <div>
            <div class="pull-left">
                <grp-custom-filter-bar @onClickNew="onClickNew"></grp-custom-filter-bar>
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

        <vuetable ref="grpvuetable"
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
            <vuetable-pagination-info ref="grppaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="grppagination"
                                 :css="css.pagination"
                                 :icons="css.icons"
                                 @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import * as api from './../../../config';
    import GroupResourcePermissionCustomActions from './GroupResourcePermissionCustomActions.vue'
    import GroupResourcePermissionFilterBar from './GroupResourcePermissionFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);

    Vue.component('grp-custom-actions', GroupResourcePermissionCustomActions);
//    Vue.component('grp-custom-filter-bar', GroupResourcePermissionFilterBar);

    export default {
        computed: {
            url(){
                return this.selectedRole? (api.currentGrpNodes + '?selectedRole=' + this.selectedRole) : '';
            }
        },
        props: {
            selectedRole: '',
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'grp-custom-filter-bar': GroupResourcePermissionFilterBar,
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
                        title: 'Group Name',
                        name: 'name',
                        sortField: 'name',
                    },
                    {
                        title: 'Role',
                        name: 'role.name',
                        sortField: 'roles.name',
                    },
                    {
                        title: 'ResourceType | Resource | Permission',
                        name: 'group_resource_permissions',
                        callback: 'getGrpLabel'
                    },
                    {
                        name: '__component:grp-custom-actions',
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
                    { field: 'name', sortField: 'groups.id', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onClickNew() {
                console.log('grpListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', selectedRole: this.selectedRole, data: null, index: ''},
                };
                this.$store.dispatch('setGrpShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.grppagination.setPaginationData(paginationData);
                this.$refs.grppaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.grpvuetable.changePage(page)
            },
            onActions (data) {
                console.log('grpListTable onActions', data);
                data.selectedRole = this.selectedRole;
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
                        text: 'You will not be able to recover this group resource permission settings!',
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
                        me.$store.dispatch('deleteGrpRequest', data.data)
                            .then((response) => {
                                console.log('deleteGrpRequest fire events -> refreshGrpTable');
                                me.$events.fire('refreshGrpTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setGrpShowModal', payload);
            },
            getGrpLabel(group_resource_permissions)
            {
                console.log('getGrpLabel group_resource_permissions=', group_resource_permissions);
                let ret = '';
                let resourceName = '';
                let pageName = '';
                let parentName = '';
                let componentName = '';
                group_resource_permissions.forEach(function(item, index, arr){
                    console.log('group_resource_permissions item=', item);
                    if (!item.resource) return;
                    switch (parseInt(item.resource_type.id))
                    {
                        case 1:
                            // Location
                            resourceName = item.resource.state.name + '/' + item.resource.name;
                            break;
                        case 2:
                            // Menu
                            if (parseInt(item.resource.parent.id) && (parseInt(item.resource.parent.id) !=1))
                                resourceName = item.resource.parent.name + '/' + item.resource.name;
                            else
                                resourceName = item.resource.name;
                            break;
                        case 3:
                            // Tab
                            pageName = item.resource.page ? item.resource.page.name + '/' : '';
                            resourceName = pageName + item.resource.name;
                            break;
                        case 4:
                            // Page
                            resourceName = item.resource.name;
                            break;
                        case 5:
                            // Component
                            pageName = item.resource.page ? item.resource.page.name + '/' : '';
                            parentName = item.resource.parent ? item.resource.parent.name + '/' : '';
                            resourceName = pageName + parentName + item.resource.name;
                            break;
                        case 6:
                            // Process
                            pageName = item.resource.component.page ? item.resource.component.page.name + '/' : '';
                            parentName = item.resource.component.parent ? item.resource.component.parent.name + '/' : '';
                            componentName = item.resource.component ? item.resource.component.name + '/' : '';
                            resourceName = pageName + parentName + componentName + item.resource.name;
                            break;
                    }
                    ret = ret + '<div>' +  item.resource_type.name
                        + ' | ' + resourceName
                        + ' | ' + item.permission.name + '</div>';

                });
                return ret;
            }
        },
        events: {
            'grp-filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.grpvuetable.refresh() )
            },
            'grp-filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.grpvuetable.refresh() )
            },
            'refreshGrpTable'() {
                console.log('events -> refreshGrpTable');
                Vue.nextTick( () => {
                    if (this.$refs.grpvuetable)
                        this.$refs.grpvuetable.refresh()
                    }
                );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.grpvuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.grppagination.setPaginationData(this.$refs.grpvuetable.tablePagination)
                })
            },
            url() {
                console.log('watch url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.grpvuetable.refresh()
                })
            }
        },
    }
</script>
<style scoped>
    /*.pagination {*/
        /*margin: 0;*/
        /*float: right;*/
    /*}*/
    /*.pagination a.page {*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 10px;*/
        /*margin-right: 2px;*/
    /*}*/
    /*.pagination a.page.active {*/
        /*color: white;*/
        /*background-color: #337ab7;*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 10px;*/
        /*margin-right: 2px;*/
    /*}*/
    /*.pagination a.btn-nav {*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 7px;*/
        /*margin-right: 2px;*/
    /*}*/
    /*.pagination a.btn-nav.disabled {*/
        /*color: lightgray;*/
        /*border: 1px solid lightgray;*/
        /*border-radius: 3px;*/
        /*padding: 5px 7px;*/
        /*margin-right: 2px;*/
        /*cursor: not-allowed;*/
    /*}*/
    /*.pagination-info {*/
        /*float: left;*/
    /*}*/
    /*.filter-bar {*/
        /*padding: 10px;*/
    /*}*/
</style>