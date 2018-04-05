<template>
    <div>
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <task-mapping-list>
                        </task-mapping-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import TaskMappingList from './TaskMappingList.vue'

    export default {
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
            'task-mapping-list' : TaskMappingList,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
