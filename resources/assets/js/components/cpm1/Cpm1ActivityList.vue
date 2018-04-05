<template>
    <div id="cpmactivity-list">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
           <div class="panel-heading"><div class="cpmactivity-title"> <span class="pull-left">Activities </span> </div> </div>
            <div id="cpm1activitylist" class="panel-body"> <cpm1activity-list-table ></cpm1activity-list-table>  </div>
                        </div> <!--  panel panel-primary  -->
                    </div>
                </div>
            </div>
        </div><!--  closed-main  -->
    </div> <!--  closed-<div id="cpmactivity-list">  -->
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import Cpm1ActivityListTable from './Cpm1ActivityListTable.vue'

    export default 
    {
        data () {  return {     }   },
        computed: {  ...mapGetters({   cpmactivityNodes: 'allCpm1ActivityNodes',    }),
            ...mapState({  user: state => state.authUser,   }),  },
        created() {   console.log('/cpm1/Cpm1activitylist.vue--Component created.');   },
        components: { 'cpm1activity-list-table': Cpm1ActivityListTable,    },
        mounted() {   console.log('/cpm1/Cpm1activitylist.vue-- Component mounted.');       },
        methods: {    },
        watch: {     }
    }
</script>

<style scoped>
       .cpmactivity-title { margin-left: 6px; height: 20px;  }
</style>