<template>
    <div >
        <div>
            <div class="pull-left">
                <notification-list-filter-bar></notification-list-filter-bar>
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

        <vuetable ref="notificationListVuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  :row-class="onRowClass"
                  trackBy="id"
                  :sort-order="sortOrder"
                  :multi-sort="true"
                  detail-row-component="notification-detail-row"
                  @vuetable:cell-clicked="onCellClicked"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="notificationPaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="notificationPagination"
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
    import NotificationListFilterBar from './NotificationListFilterBar.vue'
    import NotificationDetailRow from './NotificationDetailRow.vue'

    Vue.component('notification-detail-row', NotificationDetailRow);
    export default {
        computed: {
//            url(){
//                this.moreParams = {
//                };
//                return api.currentNotificationList;
//            },
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser
            }),
        },
        props: {
        },
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'notification-list-filter-bar': NotificationListFilterBar,
        },
        data () {
            return {
                url: api.currentNotificationList,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Type',
                        name: 'type',
                        callback: 'setType'
                    },
                    {
                        title: 'Title',
                        name: 'title',
                    },
                    {
                        title: 'Date',
                        name: 'created_at',
                        titleClass: 'hidden-xs hidden-sm',
                        dataClass: 'hidden-xs hidden-sm',
                        callback: 'formatDate|lll',
                    },
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered table-hover',
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
                    { field: 'created_at', sortField: 'messages.created_at', direction: 'desc'}
                ],
                moreParams: {
                    filter: {
                        status: 'UnRead',
                        filterText: '',
                    }
                }
            }
        },
        methods: {
            onCellClicked (data, field, event) {
                console.log('cellClicked: ', data);
                this.$refs.notificationListVuetable.toggleDetailRow(data.id);
                let readById = parseInt(data.read_by_id? data.read_by_id : 0);
                if (!readById)
                {
                    // send request to update status from unread to read
                    this.$store.dispatch('readNotification', data)
                        .then((response) => {
                            console.log('readNotification response=', response);
                            // Vue.nextTick( () => this.$refs.notificationListVuetable.refresh() );
                        })
                        .catch((error) => {
                            console.log('readNotification error=', error);
                        });
                }
            },
            onRowClass(dataItem, index) {
                console.log('onRowClass. dataItem=', dataItem);
                console.log('onRowClass. index=', index);
                let read_at = this.formatDate(dataItem.read_at, 'YYYY-MM-DD');
                let created_at = this.formatDate(dataItem.created_at, 'YYYY-MM-DD');
                let current = this.formatDate(this.getCurrentDatetime(), 'YYYY-MM-DD');
                console.log('onRowClass. read_at=', read_at);
                console.log('onRowClass. current=', current);
                console.log('onRowClass. created_at=', created_at);
                let readById = parseInt(dataItem.read_by_id? dataItem.read_by_id : 0);

                if (readById)
                    return 'read';
                else
                    return 'unread';
            },
            setType(value) {
                console.log('setType value=', value);
                switch (value)
                {
                    case 'OVERDUE':
                        return '<span class="label label-danger">' + value + '</span>';
                        break;
                    case 'DUETODAY':
                        return '<span class="label label-warning">' + value + '</span>';
                        break;
                    case 'DUETOMORROW':
                        return '<span class="label label-info">' + value + '</span>';
                        break;
                }
            },
            onPaginationData (paginationData) {
                this.$refs.notificationPagination.setPaginationData(paginationData);
                this.$refs.notificationPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.notificationListVuetable.changePage(page)
            },
        },
        events: {
            'notification-list-filter-set' (filterData) {
                this.moreParams = {
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.notificationListVuetable)
                        this.$refs.notificationListVuetable.refresh()
                } )
            },
            'notification-list-filter-reset' (filterData) {
                this.moreParams = {
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.notificationListVuetable)
                        this.$refs.notificationListVuetable.refresh()
                } )
            },
            'refreshNotificationListTable'() {
                console.log('events -> refreshNotificationListTable');
                Vue.nextTick( () => {
                    if (this.$refs.notificationListVuetable)
                        this.$refs.notificationListVuetable.refresh()
                } );
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.notificationListVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.notificationPagination.setPaginationData(this.$refs.notificationListVuetable.tablePagination)
                })
            },
        },
    }
</script>

<style>
</style>
