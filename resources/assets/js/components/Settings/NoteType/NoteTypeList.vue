<template>
    <div id="root-note-type-list">
        <ibox title="Note Types" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <note-type-list-table></note-type-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import IBox from '../../IBox/IBox.vue'
    import NoteTypeListTable from './NoteTypeListTable.vue'

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
            console.log('NoteTypeList vue created!');
        },
        mounted() {
            console.log('NoteTypeList vue mounted');
        },
        components: {
            'ibox': IBox,
            'note-type-list-table': NoteTypeListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
