<template>
    <div class="task-group-list-filter-bar filter-bar">
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <bs-input placeholder="task name ..." type="text" :rows="1" :maxlength="255" v-model="filterText"></bs-input>
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    export default {
        components: {
            'bs-input': input,
        },
        props: {
        },
        data () {
            return {
                filterText: '',
            }
        },
        created() {
        },
        methods: {
            doFilter () {
                this.$events.fire('task-group-list-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = '';
                this.$events.fire('task-group-list-filter-reset')
            },
        }
    }
</script>
