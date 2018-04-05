<template>
    <div id="root-note-type-action-list">
        <ibox title="Note Type and Action" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <note-type-action-list-table></note-type-action-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import IBox from '../../IBox/IBox.vue'
    import NoteTypeActionListTable from './NoteTypeActionListTable.vue'

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
            console.log('NoteTypeActionList vue created!');
        },
        mounted() {
            console.log('NoteTypeActionList vue mounted');
        },
        components: {
            'ibox': IBox,
            'note-type-action-list-table': NoteTypeActionListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
