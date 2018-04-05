<template>
    <div id="deviceType-list">
        <div class="main">
            <div class="container">
                <deviceType-crud-modal></deviceType-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="deviceType-title">
                                    <span class="pull-left">DeviceType
                                    </span>
                                </div>
                            </div>
                            <div id="deviceTypelist" class="panel-body table-responsive">
                                <deviceType-list-table ></deviceType-list-table>
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
    import DeviceTypeListTable from './DeviceTypeListTable.vue'
    import DeviceTypeCrudModal from './DeviceTypeCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                deviceTypeNodes: 'allDeviceTypeNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('DeviceTypeList vue Component created.');
        },
        components: {
            'deviceType-crud-modal': DeviceTypeCrudModal,
            'deviceType-list-table': DeviceTypeListTable,
        },
        mounted() {
            console.log('DeviceTypeList vue Component mounted.');
        },
        methods: {
        },
        watch: {
        }
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
    .deviceType-title {
        margin-left: 6px;
        height: 30px;
    }
</style>