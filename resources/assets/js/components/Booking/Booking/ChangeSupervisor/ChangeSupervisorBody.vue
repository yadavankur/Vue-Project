<template>
    <div>
        <div class="change-supervisor-body">
            <change-supervisor-list-table
                    @newSupervisor="newSupervisor"
                    @selectSupervisor="selectSupervisor"
                    :order="order">
            </change-supervisor-list-table>
        </div>
    </div>
</template>

<script>
    import ChangeSupervisorBodyListTable from './ChangeSupervisorBodyListTable.vue'
    export default {
        props: {
            order: null
        },
        data () {
            return {
            }
        },
        created() {
            console.log('ChangeSupervisorBody Component created.')
        },
        components: {
            'change-supervisor-list-table': ChangeSupervisorBodyListTable,
        },
        mounted() {
            console.log('ChangeSupervisorBody Component mounted.')
        },
        methods:
        {
            newSupervisor() {
                console.log('ChangeSupervisorBody -> newSupervisor.');
                this.$emit('newSupervisor');
            },
            selectSupervisor(dataItem) {
                console.log('ChangeSupervisorBody -> selectSupervisor. dataItem=', dataItem);
                this.$emit('selectSupervisor', dataItem);
            }
        }
    }
</script>

<style scoped>

</style>
