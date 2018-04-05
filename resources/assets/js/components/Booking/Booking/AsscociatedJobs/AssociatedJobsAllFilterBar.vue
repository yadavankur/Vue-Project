<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <bs-input placeholder="so no or description..." type="text" :rows="1" :maxlength="255" v-model="filterText"></bs-input>
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
            quoteNumber: {
                type: String,
                default: '',
            }
        },
        data () {
            return {
                filterText: this.quoteNumber,
            }
        },
        created() {
        },
        methods: {
            doFilter () {
                this.$events.fire('associated-jobs-all-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = this.quoteNumber;
                this.$events.fire('associated-jobs-all-filter-reset')
            },
        }
    }
</script>
