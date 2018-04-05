<template>
    <div>
        <note-type-action-modal
                :isShowNoteTypeAction="isShowNoteTypeAction"
                :title="title"
                :actionType="actionType"
                :itemData="itemData"
                :locationOptions="locationOptions"
                :noteTypeOptions="noteTypeOptions"
                @onCloseNoteTypeActionModal="onCloseNoteTypeActionModal"
        >
        </note-type-action-modal>
        <div class="row-content">
            <div class="pull-left">
                <note-type-action-filter-bar
                        ref="noteTypeActionFilterBar"
                        @onNewNoteTypeAction="onNewNoteTypeAction"
                >
                </note-type-action-filter-bar>
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
            <note-type-action-vue-table ref="noteTypeActionVueTable"
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
                <template slot="note-type-action-slot-action" scope="props">
                    <div>
                        <button class="btn btn-primary btn-sm" @click="updateNoteTypeAction(props.rowData)" title="Update">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-danger btn-sm" @click="deleteNoteTypeAction(props.rowData)" title="Delete">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </div>
                </template>
            </note-type-action-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="noteTypeActionPaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="noteTypeActionPagination"
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
    import NoteTypeActionFilterBar from './NoteTypeActionFilterBar.vue'
    import NoteTypeActionModal from './NoteTypeActionCrudModal.vue'

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
            console.log('NoteTypeActionListTable -> created');
        },
        components: {
            'note-type-action-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'note-type-action-filter-bar': NoteTypeActionFilterBar,
            'note-type-action-modal': NoteTypeActionModal,
        },
        data () {
            return {
                locationOptions: [],
                noteTypeOptions: [],
                title: '',
                isShowNoteTypeAction: false,
                itemData: null,
                actionType: '',
                url: api.currentNoteTypeActionList,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Location',
                        name: 'location.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Note Type',
                        name: 'note_type.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Action Type(SQL Statement)',
                        name: 'action',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:note-type-action-slot-action',
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
                    { field: 'id', sortField: 'locations.name', direction: 'asc'}
                ],
                moreParams: {},
            }
        },
        methods: {
            onNewNoteTypeAction() {
                console.log('onNewNoteTypeAction.');
                this.title = 'Adding New Note Type Action';
                this.itemData = null;
                this.actionType = 'added';
                this.locationOptions = this.$refs.noteTypeActionFilterBar.locationOptions;
                this.noteTypeOptions = this.$refs.noteTypeActionFilterBar.noteTypeOptions;
                this.onOpenNoteTypeActionModal();
            },
            updateNoteTypeAction(dataItem) {
                console.log('updateNoteTypeAction. dataItem=', dataItem);
                this.title = 'Updating Note Type Action';
                this.itemData = dataItem;
                this.actionType = 'updated';
                this.locationOptions = this.$refs.noteTypeActionFilterBar.locationOptions;
                this.noteTypeOptions = this.$refs.noteTypeActionFilterBar.noteTypeOptions;
                this.onOpenNoteTypeActionModal();
            },
            deleteNoteTypeAction(dataItem) {
                console.log('deleteNoteTypeAction. dataItem=', dataItem);
                // delete
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this action!',
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
                            location_id: dataItem.location_id,
                            note_type_id: dataItem.note_type_id,
                            action: dataItem.action,
                            comment: dataItem.comment,
                            type: 'deleted',
                        };
                        this.$store.dispatch('updateNoteTypeActionRequest', formData)
                            .then((response) => {
                                console.log('updateNoteTypeActionRequest fire events -> refreshNoteTypeActionListTable');
                                this.$events.fire('refreshNoteTypeActionListTable');
                                })
                            .catch((error) => {});
                        }, (dismiss) => {});
            },
            onPaginationData (paginationData) {
                console.log('NoteTypeActionListTable -> onPaginationData');
                this.$refs.noteTypeActionPagination.setPaginationData(paginationData);
                this.$refs.noteTypeActionPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('NoteTypeActionListTable -> onChangePage');
                this.$refs.noteTypeActionVueTable.changePage(page)
            },
            onOpenNoteTypeActionModal() {
                console.log('onOpenNoteTypeActionModal.');
                this.isShowNoteTypeAction = true;
            },
            onCloseNoteTypeActionModal() {
                console.log('onCloseNoteTypeActionModal.');
                this.isShowNoteTypeAction = false;
            },
        },
        events: {
            'note-type-action-list-filter-set' (filterData) {
                console.log('note-type-action-list-filter-set Data=', filterData);
                //this.url = api.currentNoteTypeActionList;
                this.moreParams = {
                    locationId: filterData.location.id,
                    noteTypeId: filterData.noteType.id,
                };
                Vue.nextTick( () => {
                    if (this.$refs.noteTypeActionVueTable)
                        this.$refs.noteTypeActionVueTable.refresh();
                });
            },
            'note-type-action-list-filter-reset' () {
                console.log('note-type-action-list-filter-reset');
                //this.url = api.currentNoteTypeActionList;
                this.moreParams = {
                    locationId: '',
                    noteTypeId: '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.noteTypeActionVueTable)
                        this.$refs.noteTypeActionVueTable.refresh()
                });
            },
            'refreshNoteTypeActionListTable'() {
                console.log('events -> refreshNoteTypeActionListTable');
                Vue.nextTick( () => {
                    if (this.$refs.noteTypeActionVueTable)
                        this.$refs.noteTypeActionVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('NoteTypeActionListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.noteTypeActionVueTable)
                        this.$refs.noteTypeActionVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('NoteTypeActionListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.noteTypeActionPagination.setPaginationData(this.$refs.noteTypeActionVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>
