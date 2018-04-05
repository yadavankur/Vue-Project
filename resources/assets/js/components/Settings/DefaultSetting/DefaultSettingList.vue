<template>
    <div id="root-default-setting-list">
        <ibox title="System Settings" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <default-setting-list-table></default-setting-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import IBox from '../../IBox/IBox.vue'
    import DefaultSettingListTable from './DefaultSettingListTable.vue'

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
            console.log('DefaultSettingList vue created!');
        },
        mounted() {
            console.log('DefaultSettingList vue mounted');
        },
        components: {
            'ibox': IBox,
            'default-setting-list-table': DefaultSettingListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
