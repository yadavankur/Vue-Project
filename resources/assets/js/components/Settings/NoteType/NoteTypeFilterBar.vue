<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm ">
            <div class="form-group">
                <label>Search for:</label>
                <input type="text" v-model="filterText" class="form-control" @keyup.enter="doFilter" placeholder="name">
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-primary btn-sm" title="New" @click.prevent="onNewNoteType">
                    <span class="glyphicon glyphicon-plus"></span>NEW
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    import * as api from '../../../config';

    export default {
        created() {
            console.log('NoteTypeFilterBar Component created.');
        },
        components: {
            'bs-input': input,
        },
        data () {
            return {
                filterText: '',
            }
        },
        methods: {
            doFilter () {
                console.log('doFilter');
                this.$events.fire('note-type-list-filter-set', this.filterText);
            },
            resetFilter () {
                console.log('resetFilter');
                this.filterText = '';
                this.$events.fire('note-type-list-filter-reset')
            },
            onNewNoteType() {
                console.log('NoteTypeFilterBar onNewNoteType');
                this.$emit('onNewNoteType');
            }
        }
    }
</script>
<style scoped>
</style>