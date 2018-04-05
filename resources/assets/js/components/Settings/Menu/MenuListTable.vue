<template>
    <div>
        <div>
            <div class="pull-left">
                <menu-custom-filter-bar @onClickNew="onClickNew"></menu-custom-filter-bar>
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

        <vuetable ref="menuvuetable"
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
            <vuetable-pagination-info ref="menupaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="menupagination"
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
    import MenuCustomActions from './MenuCustomActions.vue'
    import MenuCustomFilterBar from './MenuCustomFilterBar.vue'
//    import VueSweetAlert from 'vue-sweetalert'
//    import VueEvents from 'vue-events'
//    Vue.use(VueEvents);
//    Vue.use(VueSweetAlert);

    Vue.component('menu-custom-actions', MenuCustomActions);
//    Vue.component('menu-custom-filter-bar', MenuCustomFilterBar);

    export default 
    {
        computed: {  url(){   return api.currentMenuNodes;  } },
        props: {     },
        components: { Vuetable,  VuetablePagination, VuetablePaginationInfo, 'menu-custom-filter-bar': MenuCustomFilterBar, },
        data () 
        {   return {  paginationPath: '', search: '',  perPage: 5,
                      fields: 
                       [   {  name: 'id', title: '#', titleClass: 'text-right', dataClass: 'text-right' },
                           {  title: 'Menu Name',  name: 'name', sortField: 'cmenus.name', },
                           {  title: 'Url Name', name: 'link', sortField: 'link', },
                           {   title: 'Parent Name', name: 'parent.name', sortField: 'pmenus.name', },
                           {  title: 'Comment', name: 'comment', },
                           {  name: '__component:menu-custom-actions', title: 'Actions', titleClass: 'text-center',
                              dataClass: 'text-center'
                           }
                       ],
                    css: {  table: {
                            tableClass: 'table table-bordered table-striped table-hover',
                            ascendingIcon: 'glyphicon glyphicon-chevron-up',
                            descendingIcon: 'glyphicon glyphicon-chevron-down'
                                    },
                            pagination: {wrapperClass: 'pagination', activeClass: 'active', disabledClass: 'disabled', pageClass: 'page', linkClass: 'link', },
                            icons: {    first: 'glyphicon glyphicon-step-backward',
                                        prev: 'glyphicon glyphicon-chevron-left',
                                        next: 'glyphicon glyphicon-chevron-right',
                                        last: 'glyphicon glyphicon-step-forward',
                                    },
                            },
                sortOrder: [ { field: 'name', sortField: 'cmenus.level', direction: 'asc'} ],
                moreParams: {}
            }
        },
        methods: 
        {   onClickNew() 
            {   console.log('/settings/menu/MenuListTable.vue--menuListTable onClickNew');
                let payload = {   isShow: true, data: {action: 'Add', data: null, index: ''}, };
                this.$store.dispatch('setMenuShowModal', payload);
            },
            onPaginationData (paginationData) 
            {
                this.$refs.menupagination.setPaginationData(paginationData);
                this.$refs.menupaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {   this.$refs.menuvuetable.changePage(page)     },
            onActions (data) 
            {   console.log('/settings/menu/MenuListTable.vue-- onActions', data);
                let payload = { isShow: true, data: data, };
                if (data.action === 'Delete')
                {   let swal = this.$swal;
                    let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this menu!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        allowOutsideClick: false
                    }).then(function() 
                    {   me.$store.dispatch('deleteMenuRequest', data.data)
                            .then((response) => {
                                console.log('/settings/menu/MenuListTable.vue--deleteMenuRequest fire events -> refreshMenuTable');
                                me.$events.fire('refreshMenuTable');
                            })
                            .catch((error) => {});
                    }, function (dismiss) {                    });
                    return;
                }
                this.$store.dispatch('setMenuShowModal', payload);
            },
        },
        events: 
        { 'menu-filter-set' (filterText)  {   this.moreParams = { filter: filterText },       Vue.nextTick( () => this.$refs.menuvuetable.refresh() )  },
            'menu-filter-reset' () {  this.moreParams = {},  Vue.nextTick( () => this.$refs.menuvuetable.refresh() ) },
            'refreshMenuTable'() {   console.log('/settings/menu/MenuListTable.vue--events -> refreshMenuTable');    Vue.nextTick( () => this.$refs.menuvuetable.refresh() );    }
        },
        watch: 
        {   'perPage' (newVal, oldVal) {  this.$nextTick(function() { this.$refs.menuvuetable.refresh()  })  },
            'paginationComponent' (newVal, oldVal)  {   this.$nextTick(function() { this.$refs.menupagination.setPaginationData(this.$refs.menuvuetable.tablePagination)  })  },
             url() { console.log('/settings/menu/MenuListTable.vue--watch +++++++ url() = ', this.url);
                    this.$nextTick(function() {  this.$refs.menuvuetable.refresh()   })
                  }
        },
    }
</script>
<style>
    .pagination {   margin: 0;  float: right; }
    .pagination a.page { border: 1px solid lightgray;   border-radius: 3px;    padding: 5px 10px;    margin-right: 2px;
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