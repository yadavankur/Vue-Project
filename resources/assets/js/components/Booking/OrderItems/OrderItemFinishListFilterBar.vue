<template>
    <div class="finish-filter-bar">
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <bs-input placeholder="Item No..." type="number" v-model="filterData.itemNo"></bs-input>
                <bs-input placeholder="Finish name..." type="text" :rows="1" :maxlength="255" v-model="filterData.filterText"></bs-input>
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
                filterData: {
                    itemNo: '',
                    filterText: '',
                }

            }
        },
        created() {
        },
        methods: {
            doFilter () {
                this.$events.fire('order-finish-list-filter-set', this.filterData)
            },
            resetFilter () {
                this.filterData = {
                    itemNo: '',
                    filterText: '',
                };
                this.$events.fire('order-finish-list-filter-reset')
            },
        }
    }
</script>
