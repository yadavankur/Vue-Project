<template>
    <div>
        <div>
            <div class="pull-left">
                <tab-custom-filter-bar @onClickNew="onClickNew"></tab-custom-filter-bar>
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

        <vuetable ref="tabvuetable"
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
            <vuetable-pagination-info ref="tabpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="tabpagination"
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
    import * as api from './../../config';
    import Tab1CustomActions from './Tab1CustomActions.vue'
    import Tab1CustomFilterBar from './Tab1CustomFilterBar.vue'


    Vue.component('tab-custom-actions', Tab1CustomActions);
    Vue.component('tab-custom-filter-bar', Tab1CustomFilterBar);

export default 
 {  computed: {   url(){ return api.currentTab1Nodes;  }    },
    props: {     },
    components: {  Vuetable, VuetablePagination, VuetablePaginationInfo,'tab-custom-filter-bar': Tab1CustomFilterBar, },
    data () { return {  paginationPath: '',  search: '',   perPage: 5,
                        fields: [  { name: 'id',title: '#', titleClass: 'text-right',   dataClass: 'text-right' },
                                 {  title: 'Tab Name',  name: 'name',  sortField: 'name',  },
                                 { title: 'Url Path',  name: 'link',               },
                                 {  title: 'Comment',  name: 'comment',   },
                                 { title: 'Page', name: 'page.name',  },
                                 { name: '__component:tab-custom-actions', title: 'Actions',titleClass: 'text-center',   dataClass: 'text-center'   }
                               ],
                       css: {    table: {   tableClass: 'table table-bordered table-striped table-hover',
                                            ascendingIcon: 'glyphicon glyphicon-chevron-up',
                                            descendingIcon: 'glyphicon glyphicon-chevron-down'
                                        },
                                 pagination: { wrapperClass: 'pagination',  activeClass: 'active',  disabledClass: 'disabled',
                                               pageClass: 'page', linkClass: 'link',
                                             },
                                 icons: {  first: 'glyphicon glyphicon-step-backward', prev: 'glyphicon glyphicon-chevron-left',
                                           next: 'glyphicon glyphicon-chevron-right', last: 'glyphicon glyphicon-step-forward',
                                        },
                            },
                         sortOrder: [  { field: 'name', sortField: 'name', direction: 'asc'}     ],
                         moreParams: {}
                    }
                 },
        methods: {
            onClickNew() 
            {   console.log('tabListTable onClickNew');
                let payload = {isShow: true, data: {action: 'Add', data: null, index: ''}, };
                this.$store.dispatch('setTabShowModal', payload);
            },
            onPaginationData (paginationData) 
            {   this.$refs.tabpagination.setPaginationData(paginationData);
                this.$refs.tabpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) 
            {   this.$refs.tabvuetable.changePage(page)    },
            onActions (data) 
            {   console.log('tabListTable onActions', data);
                let payload = { isShow: true, data: data, };
                if (data.action === 'Delete')
                {   let swal = this.$swal; let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this tab!',
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
                        me.$store.dispatch('deleteTabRequest', data.data)
                            .then((response) => {
                                console.log('deleteTabRequest fire events -> refreshTabTable');
                                me.$events.fire('refreshTabTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {     });
                    return;
                }
                this.$store.dispatch('setTabShowModal', payload);
            },
        },
        events: {
                    'tab-filter-set' (filterText) { this.moreParams = { filter: filterText }
                                            Vue.nextTick( () => this.$refs.tabvuetable.refresh() )
                                          },
                    'tab-filter-reset' () { this.moreParams = {}
                                    Vue.nextTick( () => this.$refs.tabvuetable.refresh() )
                                  },
                    'refreshTabTable'() { console.log('events -> refreshTabTable');
                                  Vue.nextTick( () => this.$refs.tabvuetable.refresh() );
                                }
                },
        watch: { 'perPage' (newVal, oldVal) { this.$nextTick(function() { this.$refs.tabvuetable.refresh()  })   },
                 'paginationComponent' (newVal, oldVal) 
                    {  this.$nextTick(function() {  this.$refs.tabpagination.setPaginationData(this.$refs.tabvuetable.tablePagination)  })
                    },
                  url(){  console.log('/newtab1/Tab1ListTable.vue-- watch url() = ', this.url);
                          this.$nextTick(function() {  this.$refs.tabvuetable.refresh() })
                       }
               },
    }
</script>
<style scoped>
    .pagination { margin: 0; float: right;  }
    .pagination a.page {  border: 1px solid lightgray; border-radius: 3px; padding: 5px 10px; margin-right: 2px; }
    .pagination a.page.active {
        color: white;  background-color: #337ab7;  border: 1px solid lightgray;
        border-radius: 3px;  padding: 5px 10px;  margin-right: 2px;
    }
    .pagination a.btn-nav { border: 1px solid lightgray; border-radius: 3px; padding: 5px 7px; margin-right: 2px; }
    .pagination a.btn-nav.disabled {
        color: lightgray;  border: 1px solid lightgray;   border-radius: 3px;
        padding: 5px 7px;   margin-right: 2px;   cursor: not-allowed;
    }
    .pagination-info {  float: left; }
    .filter-bar {   padding: 10px;  }
</style>