<template>
    <div>
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <default-setting-list>
                        </default-setting-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import DefaultSettingList from './DefaultSettingList.vue'

    export default {
        computed: {
        },
        data () {
            return {
            }
        },
        created() {
            console.log('DefaultSettingList vue created!');
        },
        mounted() {
            console.log('DefaultSettingList vue mounted');
        },
        components: {
            'default-setting-list' : DefaultSettingList,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
