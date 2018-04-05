<template>
    <div id="root-ec-booking-list-result">
        <div class="app-row" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#result-section">
                        Search Result Section
                    </a>
                </div>
                <div id="result-section" class="panel-collapse collapse in table-responsive">
                    <div class="row-content">
                        <ec-booking-list-table @onSelectRow="onSelectRow"></ec-booking-list-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import ECBookingListTable from './BookingListTable.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ECBookingListResultSection vue created!');
        },
        data () {
            return {}
        },
        components: {
            'ec-booking-list-table': ECBookingListTable,
        },
        methods: {
            onSelectRow(rowItem)
            {
                console.log('ECBookingListResultSection -> onSelectRow rowItem=', rowItem);
                this.$emit('onSelectRow', rowItem);
            }
        }
    }
</script>

<style scoped>
</style>