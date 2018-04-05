<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm ">
            <div class="form-group">
                <label>Search for:</label>
                <Select clearable filterable v-model="formSearchData.location.id"
                    @on-change="onChangeLocation"
                    placeholder="Please select a location..."
                    style="width:180px"
                >
                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <Select clearable filterable v-model="formSearchData.noteType.id"
                        @on-change="onChangeNoteType"
                        placeholder="Please select a note type..."
                        style="width:220px"
                >
                    <Option v-for="item in noteTypeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-primary btn-sm" title="New" @click.prevent="onNewNoteTypeAction">
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
            console.log('NoteTypeActionFilterBar Component created.');
            this.getAssingedLocations();
            this.getNoteTypeOptions();
        },
        components: {
            'bs-input': input,
        },
        data () {
            return {
                formSearchData: {
                    location: {id: '', name: ''},
                    noteType: {id: '', name: ''},
                },
                locationOptions: [],
                noteTypeOptions: [],
            }
        },
        methods: {
            onChangeLocation(val) {
                console.log('onChangeLocation val=',val);
            },
            onChangeNoteType(val) {
                console.log('onChangeNoteType val=',val);
            },
            getNoteTypeOptions() {
                this.$store.dispatch('getNoteTypeOptions')
                    .then((response) => {
                        console.log('getNoteTypeOptions success=', response);
                        this.noteTypeOptions = response.body;
                    })
                    .catch((error) => {
                        console.error('getNoteTypeOptions error=', error);
                    });
            },
//            setNoteTypeOptions(elements) {
//                console.log('setAssignedLocationOptions elements=',elements);
//                let options = [];
//                for (let element in elements) {
//                    options.push({value: elements[element].id, label: elements[element].name});
//                }
//                this.noteTypeOptions = options;
//            },
            doFilter () {
                console.log('doFilter');
                this.$events.fire('note-type-action-list-filter-set', this.formSearchData);
            },
            resetFilter () {
                console.log('resetFilter');
                this.formSearchData.location = {id:'', name:''};
                this.formSearchData.noteType = {id:'', name:''};
                this.$events.fire('note-type-action-list-filter-reset')
            },
            onNewNoteTypeAction() {
                console.log('NoteTypeActionFilterBar onNewNoteTypeAction');
                this.$emit('onNewNoteTypeAction');
            }
        }
    }
</script>
<style scoped>
</style>