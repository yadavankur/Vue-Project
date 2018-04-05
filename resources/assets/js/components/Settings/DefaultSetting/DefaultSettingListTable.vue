<template>
    <div>
        <default-setting-modal
                :isShowDefaultSetting="isShowDefaultSetting"
                :title="title"
                :actionType="actionType"
                :itemData="itemData"
                :locationOptions="locationOptions"
                @onCloseDefaultSettingModal="onCloseDefaultSettingModal"
        >
        </default-setting-modal>
        <div class="row-content">
            <div class="pull-left">
                <default-setting-filter-bar
                        ref="defaultSettingFilterBar"
                        @onNewDefaultSetting="onNewDefaultSetting"
                >
                </default-setting-filter-bar>
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
        <div class="row-content">
            <default-setting-vue-table ref="defaultSettingVueTable"
                                 :api-url="url"
                                 :fields="fields"
                                 :per-page="perPage"
                                 :pagination-path="paginationPath"
                                 :css="css.table"
                                 :sort-order="sortOrder"
                                 trackBy="id"
                                 :multi-sort="true"
                                 :append-params="moreParams"
                                 @vuetable:pagination-data="onPaginationData">
                <template slot="default-setting-slot-action" scope="props">
                    <div>
                        <button class="btn btn-primary btn-sm" @click="updateDefaultSetting(props.rowData)" title="Update">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-danger btn-sm" @click="deleteDefaultSetting(props.rowData)" title="Delete">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </div>
                </template>
            </default-setting-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="defaultSettingPaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="defaultSettingPagination"
                                     :css="css.pagination"
                                     :icons="css.icons"
                                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import * as api from '../../../config';
    import DefaultSettingFilterBar from './DefaultSettingFilterBar.vue'
    import DefaultSettingModal from './DefaultSettingCrudModal.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        props: {
        },
        mounted() {
        },
        created() {
            console.log('DefaultSettingListTable -> created');
        },
        components: {
            'default-setting-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'default-setting-filter-bar': DefaultSettingFilterBar,
            'default-setting-modal': DefaultSettingModal,
        },
        data () {
            return {
                locationOptions: [],
                title: '',
                isShowDefaultSetting: false,
                itemData: null,
                actionType: '',
                url: api.currentDefaultSettingList,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'ID',
                        name: 'id',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Location',
                        name: 'location.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Type',
                        name: 'type',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Value',
                        name: 'value',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Description',
                        name: 'description',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:default-setting-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered',
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
                    { field: 'id', sortField: 'id', direction: 'asc'}
                ],
                moreParams: {},
            }
        },
        methods: {
            onNewDefaultSetting() {
                console.log('onNewDefaultSetting.');
                this.title = 'Adding New Setting';
                this.itemData = null;
                this.actionType = 'added';
                this.locationOptions = this.$refs.defaultSettingFilterBar.locationOptions;
                this.onOpenDefaultSettingModal();
            },
            updateDefaultSetting(dataItem) {
                console.log('updateDefaultSetting. dataItem=', dataItem);
                this.title = 'Updating Setting';
                this.itemData = dataItem;
                this.actionType = 'updated';
                this.locationOptions = this.$refs.defaultSettingFilterBar.locationOptions;
                this.onOpenDefaultSettingModal();
            },
            deleteDefaultSetting(dataItem) {
                console.log('deleteDefaultSetting. dataItem=', dataItem);
                // delete
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this setting!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    allowOutsideClick: false
                })
                    .then(() => {
                        let formData = {
                            id: dataItem.id,
                            type: dataItem.type,
                            value: dataItem.value,
                            description: dataItem.description,
                            action_type: 'deleted',
                        };
                        this.$store.dispatch('updateDefaultSettingRequest', formData)
                            .then((response) => {
                                console.log('updateDefaultSettingRequest fire events -> refreshDefaultSettingListTable');
                                this.$events.fire('refreshDefaultSettingListTable');
                            })
                            .catch((error) => {});
                    }, (dismiss) => {});
            },
            onPaginationData (paginationData) {
                console.log('DefaultSettingListTable -> onPaginationData');
                this.$refs.defaultSettingPagination.setPaginationData(paginationData);
                this.$refs.defaultSettingPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('DefaultSettingListTable -> onChangePage');
                this.$refs.defaultSettingVueTable.changePage(page)
            },
            onOpenDefaultSettingModal() {
                console.log('onOpenDefaultSettingModal.');
                this.isShowDefaultSetting = true;
            },
            onCloseDefaultSettingModal() {
                console.log('onCloseDefaultSettingModal.');
                this.isShowDefaultSetting = false;
            },
        },
        events: {
            'default-setting-list-filter-set' (filterData) {
                console.log('default-setting-list-filter-set Data=', filterData);
                this.moreParams = {
                    filter: filterData,
                };
                Vue.nextTick( () => {
                    if (this.$refs.defaultSettingVueTable)
                        this.$refs.defaultSettingVueTable.refresh();
                });
            },
            'default-setting-list-filter-reset' () {
                console.log('default-setting-list-filter-reset');
                this.moreParams = {
                    filter: '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.defaultSettingVueTable)
                        this.$refs.defaultSettingVueTable.refresh()
                });
            },
            'refreshDefaultSettingListTable'() {
                console.log('events -> refreshDefaultSettingListTable');
                Vue.nextTick( () => {
                    if (this.$refs.defaultSettingVueTable)
                        this.$refs.defaultSettingVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('DefaultSettingListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.defaultSettingVueTable)
                        this.$refs.defaultSettingVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('DefaultSettingListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.defaultSettingPagination.setPaginationData(this.$refs.defaultSettingVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>
