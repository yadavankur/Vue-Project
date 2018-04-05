<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <bs-input placeholder="so no or description..." type="text" :rows="1" :maxlength="255" v-model="filterText"></bs-input>
                <button class="btn btn-success btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-danger btn-sm"
                        :disabled="selectedOrder? false: true"
                        @click.prevent="reBookJob">RE-BOOK JOB</button>
                <button class="btn btn-primary btn-sm"
                        :disabled="selectedOrder && selectedIds.length? false: true"
                        @click.prevent="printList">
                    <i class="glyphicon glyphicon-print"></i> Print List</button>
                <button class="btn btn-info btn-sm"
                        :disabled="selectedOrder && selectedIds.length? false: true"
                        @click.prevent="emailList">
                    <i class="glyphicon glyphicon-envelope"></i> Email List</button>
            </div>
        </form>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'
    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        components: {
            'bs-input': input,
        },
        props: {
            selectedOrder: {
              type: Object,
            },
            selectedIds: {
                type: Array,
            },
        },
        data () {
            return {
                filterText: '',
            }
        },
        created() {
        },
        events: {
            'cd-confirmation-associated-jobs-reset' () {
                console.log('cd-confirmation-associated-jobs-reset');
                this.resetFilter();
            },
        },
        methods: {
            doFilter () {
                this.$events.fire('cd-confirmation-associated-jobs-list-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = '';
                this.$events.fire('cd-confirmation-associated-jobs-list-filter-reset')
            },
            reBookJob() {
                console.log('ChangeDateAssociatedJobsFilterBar -> reBookJob');
                this.$emit('onReBookJob');
            },
            printList() {
                console.log('printList');
                this.$emit('onPrintJobsList');
            },
            emailList() {
                console.log('emailList');
                this.$emit('onEmailJobsList');
            },
        }
    }
</script>
<style scoped>

</style>