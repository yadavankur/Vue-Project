<template>
    <div>
        <note-type-modal
                :isShowNoteType="isShowNoteType"
                :title="title"
                :actionType="actionType"
                :itemData="itemData"
                @onCloseNoteTypeModal="onCloseNoteTypeModal"
        >
        </note-type-modal>
        <div class="row-content">
            <div class="pull-left">
                <note-type-filter-bar
                        ref="noteTypeFilterBar"
                        @onNewNoteType="onNewNoteType"
                >
                </note-type-filter-bar>
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
            <note-type-vue-table ref="noteTypeVueTable"
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
                <template slot="note-type-slot-action" scope="props">
                    <div v-if="props.rowData.id != 1">
                        <button class="btn btn-primary btn-sm" @click="updateNoteType(props.rowData)" title="Update">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-danger btn-sm" @click="deleteNoteType(props.rowData)" title="Delete">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </div>
                </template>
            </note-type-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="noteTypePaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="noteTypePagination"
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
    import NoteTypeFilterBar from './NoteTypeFilterBar.vue'
    import NoteTypeModal from './NoteTypeCrudModal.vue'

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
            console.log('NoteTypeListTable -> created');
        },
        components: {
            'note-type-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'note-type-filter-bar': NoteTypeFilterBar,
            'note-type-modal': NoteTypeModal,
        },
        data () {
            return {
                title: '',
                isShowNoteType: false,
                itemData: null,
                actionType: '',
                url: api.currentNoteTypeList,
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
                        title: 'Note Type',
                        name: 'name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:note-type-slot-action',
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
            onNewNoteType() {
                console.log('onNewNoteType.');
                this.title = 'Adding New Note Type';
                this.itemData = null;
                this.actionType = 'added';
                this.onOpenNoteTypeModal();
            },
            updateNoteType(dataItem) {
                console.log('updateNoteType. dataItem=', dataItem);
                this.title = 'Updating Note Type';
                this.itemData = dataItem;
                this.actionType = 'updated';
                this.onOpenNoteTypeModal();
            },
            deleteNoteType(dataItem) {
                console.log('deleteNoteType. dataItem=', dataItem);
                // delete
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this note type!',
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
                            name: dataItem.name,
                            comment: dataItem.comment,
                            type: 'deleted',
                        };
                        this.$store.dispatch('updateNoteTypeRequest', formData)
                            .then((response) => {
                                console.log('updateNoteTypeRequest fire events -> refreshNoteTypeListTable');
                                this.$events.fire('refreshNoteTypeListTable');
                            })
                            .catch((error) => {});
                    }, (dismiss) => {});
            },
            onPaginationData (paginationData) {
                console.log('NoteTypeListTable -> onPaginationData');
                this.$refs.noteTypePagination.setPaginationData(paginationData);
                this.$refs.noteTypePaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('NoteTypeListTable -> onChangePage');
                this.$refs.noteTypeVueTable.changePage(page)
            },
            onOpenNoteTypeModal() {
                console.log('onOpenNoteTypeModal.');
                this.isShowNoteType = true;
            },
            onCloseNoteTypeModal() {
                console.log('onCloseNoteTypeModal.');
                this.isShowNoteType = false;
            },
        },
        events: {
            'note-type-list-filter-set' (filterText) {
                console.log('note-type-list-filter-set Data=', filterText);
                //this.url = api.currentNoteTypeList;
                this.moreParams = {
                    filter: filterText,
                };
                Vue.nextTick( () => {
                    if (this.$refs.noteTypeVueTable)
                        this.$refs.noteTypeVueTable.refresh();
                });
            },
            'note-type-list-filter-reset' () {
                console.log('note-type-list-filter-reset');
                //this.url = api.currentNoteTypeList;
                this.moreParams = {
                    filter: '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.noteTypeVueTable)
                        this.$refs.noteTypeVueTable.refresh()
                });
            },
            'refreshNoteTypeListTable'() {
                console.log('events -> refreshNoteTypeListTable');
                Vue.nextTick( () => {
                    if (this.$refs.noteTypeVueTable)
                        this.$refs.noteTypeVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('NoteTypeListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.noteTypeVueTable)
                        this.$refs.noteTypeVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('NoteTypeListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.noteTypePagination.setPaginationData(this.$refs.noteTypeVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>
