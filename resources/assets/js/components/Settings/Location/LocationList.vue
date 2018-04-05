<template>
    <div id="location-list">
        <div class="main">
            <div class="container">
                <location-crud-modal></location-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="location-title">
                                    <span class="pull-left">Location
                                    </span>
                                </div>
                            </div>
                            <div id="locationlist" class="panel-body table-responsive">
                                <location-list-table ></location-list-table>
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
    import LocationListTable from './LocationListTable.vue'
    import LocationCrudModal from './LocationCrudModal.vue'

    export default {
        data () {  return {    }     },
        computed: {  ...mapGetters({ locationNodes: 'allLocationNodes', }),
                     ...mapState({  user: state => state.authUser,   }),
                 },
        created() { console.log('/settings/location/LocationList.vue-- created.');  },
        components: {  'location-crud-modal': LocationCrudModal,
                       'location-list-table': LocationListTable,
                    },
        mounted() {  console.log('/settings/location/LocationList.vue-- mounted.');  },
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
    .location-title {
        margin-left: 6px;
        height: 30px;
    }
</style>