<template>
    <div class="filter-bar">
        <associated-jobs-new-modal
                :isShowNewModal="isShowNewModal"
                @onCloseNewModal="onCloseNewModal">
        </associated-jobs-new-modal>
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <bs-input placeholder="so no or description..." type="text" :rows="1" :maxlength="255" v-model="filterText"></bs-input>
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-primary btn-sm" @click.prevent="addNewJobs">Add New Jobs</button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    import AsscociatedJobsNewModal from './AssociatedJobsNewModal.vue'
    export default {
        components: {
            'bs-input': input,
            'associated-jobs-new-modal' : AsscociatedJobsNewModal,
        },
        props: {
        },
        data () {
            return {
                filterText: '',
                isShowNewModal: false,
            }
        },
        created() {
        },
        methods: {
            doFilter () {
                this.$events.fire('associated-jobs-list-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = '';
                this.$events.fire('associated-jobs-list-filter-reset')
            },
            addNewJobs() {
                console.log('addNewJobs');
                this.isShowNewModal = true;
            },
            onCloseNewModal() {
                console.log('onCloseNewModal');
                this.isShowNewModal = false;
            }
        }
    }
</script>
