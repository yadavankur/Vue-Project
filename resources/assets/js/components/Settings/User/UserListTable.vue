<template>
    <div>
        <div>
            <div class="pull-left">
                <user-custom-filter-bar @onClickNew="onClickNew"></user-custom-filter-bar>
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

        <vuetable ref="uservuetable"
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
            <vuetable-pagination-info ref="userpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="userpagination"
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
    import UserCustomActions from './UserCustomActions.vue'
    import UserCustomFilterBar from './UserCustomFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);

    Vue.component('user-custom-actions', UserCustomActions);
    //Vue.component('user-custom-filter-bar', UserCustomFilterBar);

    export default {
        computed: {
            url(){
                return this.selectedRole? (api.currentUserNodes + '?selectedRole=' + this.selectedRole) : '';
            }
        },
        props: {
            selectedRole: '',
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'user-custom-filter-bar': UserCustomFilterBar,
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
                        title: 'User Name',
                        name: 'name',
                        sortField: 'name',
                    },
                    {
                        title: 'User Email',
                        name: 'email',
                        sortField: 'email',
                    },
                    {
                        title: 'Group | Role',
                        name: 'usergroups',
                        callback: 'getGroupRoleLabel'
                    },
                    {
                        name: '__component:user-custom-actions',
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
                    { field: 'name', sortField: 'name', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            onClickNew() {
                console.log('userListTable onClickNew');
                let payload = {
                    isShow: true,
                    data: {action: 'Add', selectedRole: this.selectedRole, data: null, index: ''},
                };
                this.$store.dispatch('setUserShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.userpagination.setPaginationData(paginationData);
                this.$refs.userpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.uservuetable.changePage(page)
            },
            onActions (data) {
                console.log('userListTable onActions', data);
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
                        text: 'You will not be able to recover this user!',
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
                        me.$store.dispatch('deleteUserRequest', data.data)
                            .then((response) => {
                                console.log('deleteUserRequest fire events -> refreshUserTable');
                                me.$events.fire('refreshUserTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setUserShowModal', payload);
            },
            getGroupRoleLabel(usergroups)
            {
                console.log('getGroupRoleLabel usergroups=', usergroups);
                let ret = '';
                usergroups.forEach(function(item, index, arr){
                    ret = ret + '<div>' +  (item.group? item.group.name : '');
                    ret = ret + (item.group.role? '|' + item.group.role.name : '') + '</div>';
                });
                return ret;
            }
        },
        events: {
            'user-filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.uservuetable.refresh() )
            },
            'user-filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.uservuetable.refresh() )
            },
            'refreshUserTable'() {
                console.log('events -> refreshUserTable');
                Vue.nextTick( () => this.$refs.uservuetable.refresh() );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.uservuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.userpagination.setPaginationData(this.$refs.uservuetable.tablePagination)
                })
            },
            url() {
                console.log('watch url() = ', this.url);
                this.$nextTick(function() {
                    this.$refs.uservuetable.refresh()
                })
            }
        },
    }
</script>
<style scoped>
</style>