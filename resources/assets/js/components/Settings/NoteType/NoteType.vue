<template>
    <div>
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <note-type-list>
                        </note-type-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import NoteTypeList from './NoteTypeList.vue'

    export default {
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
            'note-type-list' : NoteTypeList,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
