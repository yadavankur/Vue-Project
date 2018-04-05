<template>
    <div id="cpmservice-list">
        <div class="main">
            <div class="container">
               <cpmservice-crud-modal></cpmservice-crud-modal> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"> <div class="cpmservice-title"><span class="pull-left">Services</span></div> </div>
                            <div id="cpmservicelist" class="panel-body table-responsive"> <cpmservice-list-table></cpmservice-list-table> </div>
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
    import Cpm2ServiceListTable from './Cpm2ServiceListTable.vue'
    import Cpm2ServiceCrudModal from './Cpm2ServiceCrudModal.vue'

    export default 
    {   data () {     return {       }     },     //cpm2serviceNodes changed from cpmserviceNodes--no effect--whatever name
        computed: {   ...mapGetters({  cpm2serviceNodes: 'allCpmServiceNodes',   }),  
                      ...mapState({  user: state => state.authUser,    }),  
                 },
        created() {    console.log('/cpm2/CpmServiceList.vue Component created.'); },
        components: { 'cpmservice-crud-modal': Cpm2ServiceCrudModal, 'cpmservice-list-table': Cpm2ServiceListTable,  },
        mounted() {    console.log('/cpm2/CpmServiceList.vue Component mounted.'); },
        methods: {     },
        watch: {     }
    }


</script>

<style scoped>    .cpmservice-title {   margin-left: 6px;   height: 30px;   }
</style>