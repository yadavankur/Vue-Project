<template>
    <div id="cpmactivity-list">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="cpmactivity-title">
                                    <span class="pull-left">Activities
                                    </span>
                                </div>
                            </div>
                            <!--<div id="cpmactivitylist" class="panel-body table-responsive">-->
                            <div id="cpmactivitylist" class="panel-body">
                                <cpmactivity-list-table ></cpmactivity-list-table>
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
    import CpmActivityListTable from './CpmActivityListTable.vue'

    export default 
    {
        data () {  return {     }   },
        computed: {  ...mapGetters({   cpmactivityNodes: 'allCpmActivityNodes',    }),
            ...mapState({  user: state => state.authUser,   }),  },
        created() {   console.log('js/settings/criticalpath/activity/activitylist.vue--CpmActivityList vue Component created.');   },
        components: { 'cpmactivity-list-table': CpmActivityListTable,    },
        mounted() {   console.log('js/settings/criticalpath/activity/activitylist.vue--CpmActivityList vue Component mounted.');       },
        methods: {    },
        watch: {     }
    }
</script>

<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/
    .cpmactivity-title {
        margin-left: 6px;
        height: 20px;
    }
</style>