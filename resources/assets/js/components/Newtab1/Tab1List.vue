<template>
<div id="tab-list">
    <div class="main">
        <div class="container"> <!-- <tab-crud-modal></tab-crud-modal>  -->
            <div class="row">
               <div class="col-md-12">
                   <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="tab-title"> <span class="pull-left">Tab </span> </div>
                        </div>
                        <div id="tablist" class="panel-body table-responsive">
                                <tab-list-table ></tab-list-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import Tab1ListTable from './Tab1ListTable.vue'
  // import Tab1CrudModal from './Tab1CrudModal.vue'

    export default 
    {   data () {  return {     }   },
        computed: {    ...mapGetters({   tabNodes: 'allTabNodes', }),
                       ...mapState({ user: state => state.authUser,  }),
                   },
        created() { console.log('/Newtab1/Tab1List.vue--TabList vue Component created.'); },
        components: { 
            //'tab-crud-modal': Tab1CrudModal, 
            'tab-list-table': Tab1ListTable, 
            },
        mounted() { console.log('/Newtab1/Tab1List.vue--TabList vue Component mounted.'); },
        methods: {     },
        watch: {    }
    }
</script>

<style scoped>
    .tab-title {  margin-left: 6px;  height: 30px;  }
</style>