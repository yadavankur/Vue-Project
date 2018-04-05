<template>
    <div>
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <note-type-action-list>
                        </note-type-action-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import NoteTypeActionList from './NoteTypeActionList.vue'

    export default {
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
            'note-type-action-list' : NoteTypeActionList,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
