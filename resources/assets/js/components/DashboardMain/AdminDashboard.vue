<template>
    <div id="root-admin-dashboard">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ibox title="Admin Dashboard">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    Admin Dashboard (..Need to develop based on the requirements from GMers...)
                                </div>
                            </div>
                        </ibox>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import IBox from '../IBox/IBox.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
            }
        },
        created() {
            console.log('/resources/assets/js/components/DashboardMain/AdminDashboard.vue-AdminDashboard vue created!');
        },
        mounted() {
            console.log('/resources/assets/js/components/DashboardMain/AdminDashboard.vue-AdminDashboard vue mounted');
        },
        components: {
            'ibox': IBox,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
