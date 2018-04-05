<template>
    <div id="responsiveSetting-list">
        <div class="main">
            <div class="container">
                <responsiveSetting-crud-modal
                        :deviceTypeOptions="deviceTypeOptions"
                        :cascadeComponentOptions="cascadeComponentOptions"
                >
                </responsiveSetting-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="responsiveSetting-title">
                                    <span class="pull-left">ResponsiveSetting
                                    </span>
                                </div>
                            </div>
                            <div id="responsiveSettinglist" class="panel-body table-responsive">
                                <responsiveSetting-list-table ></responsiveSetting-list-table>
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
    import ResponsiveSettingListTable from './ResponsiveSettingListTable.vue'
    import ResponsiveSettingCrudModal from './ResponsiveSettingCrudModal.vue'

    export default {
        data () {
            return {
                deviceTypeOptions: [],
                cascadeComponentOptions: [],
            }
        },
        computed: {
            ...mapGetters({
                responsiveSettingNodes: 'allResponsiveSettingNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ResponsiveSettingList vue Component created.');
            // get device type options
            this.$store.dispatch('getDeviceTypeOptions')
                .then((response) => {
                    console.log('created getDeviceTypeOptions success=', response);
                    this.setDeviceTypeOptions(response.data.deviceTypes);
                })
                .catch((error) => {
                    console.error('getDeviceTypeOptions error=', error);
                });
            // get cascade component options
            this.$store.dispatch('getCascadeComponents')
                .then((response) => {
                    console.log('created getCascadeComponents success=', response);
                    this.cascadeComponentOptions = response.data.cascadeComponents;
                })
                .catch((error) => {
                    console.error('getCascadeComponents error=', error);
                });
        },
        components: {
            'responsiveSetting-crud-modal': ResponsiveSettingCrudModal,
            'responsiveSetting-list-table': ResponsiveSettingListTable,
        },
        mounted() {
            console.log('ResponsiveSettingList vue Component mounted.');
        },
        methods: {
            setDeviceTypeOptions(deviceTypes) {
                console.log('setDeviceTypeOptions deviceTypes=',deviceTypes);
                let options = [];
                for (let deviceType in deviceTypes) {
                    options.push({value: deviceTypes[deviceType].id, label: deviceTypes[deviceType].name});
                }
                this.deviceTypeOptions = options;
            },
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
    .responsiveSetting-title {
        margin-left: 6px;
        height: 30px;
    }
</style>