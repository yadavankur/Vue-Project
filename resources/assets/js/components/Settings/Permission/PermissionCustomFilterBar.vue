<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="form-group">
                <label>Search for:</label>
                <input type="text" v-model="filterText" class="form-control" @keyup.enter="doFilter" placeholder="name">
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-primary btn-sm" title="New" @click.prevent="onClickNew">
                    <span class="glyphicon glyphicon-plus"></span>NEW
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                filterText: ''
            }
        },
        methods: {
            doFilter () {
                this.$events.fire('permission-filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = '';
                this.$events.fire('permission-filter-reset')
            },
            onClickNew() {
                console.log('PermissionCustomFilterBar onClickNew');
                this.$emit('onClickNew', {action: 'Add'});
            }
        }
    }
</script>
<!--<style scoped>-->
    <!--.filter-bar {-->
        <!--padding: 10px;-->
    <!--}-->
<!--</style>-->