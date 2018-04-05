<template>
    <div id="root-task-mapping-list">
        <ibox title="Task Mappings" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <task-mapping-list-table></task-mapping-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import IBox from '../../IBox/IBox.vue'
    import TaskMappingListTable from './TaskMappingListTable.vue'

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
            console.log('TaskMappingList vue created!');
        },
        mounted() {
            console.log('TaskMappingList vue mounted');
        },
        components: {
            'ibox': IBox,
            'task-mapping-list-table': TaskMappingListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
