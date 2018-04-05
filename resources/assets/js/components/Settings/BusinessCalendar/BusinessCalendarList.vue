<template>
    <div class="container">
        <div class="row">
            <ibox title="Business Calendar" :closeable="false">
                <div slot="ibox-content" class="ibox-content">
                    <div class="row-content">
                        <div id="business-calendar-list" class="panel-body table-responsive">
                            <business-calendar-list-table ></business-calendar-list-table>
                        </div>
                    </div>
                </div>
            </ibox>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import IBox from '../../IBox/IBox.vue'
    import BusinessCalendarListTable from './BusinessCalendarListTable.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('BusinessCalendar vue created.');
        },
        components: {
            'ibox': IBox,
            'business-calendar-list-table': BusinessCalendarListTable,
        },
        mounted() {
            console.log('BusinessCalendar vue mounted.');
        },
        methods: {
        },
    }
</script>

<style>
</style>