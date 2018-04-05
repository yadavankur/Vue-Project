<template>
    <div>
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <log-list>
                        </log-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LogList from './LogList.vue'
    export default {
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
            'log-list' : LogList,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
