<template>
    <div id="root-log-list">
        <ibox title="System Logs" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <log-list-table></log-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import IBox from '../../IBox/IBox.vue'
    import LogListTable from './LogListTable.vue'

    export default {
        props: {
        },
        computed: {
        },
        data () {
            return {
            }
        },
        created() {
            console.log('LogList vue created!');
        },
        mounted() {
            console.log('LogList vue mounted');
        },
        components: {
            'ibox': IBox,
            'log-list-table': LogListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
