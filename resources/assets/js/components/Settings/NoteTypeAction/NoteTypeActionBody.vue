<template>
    <div>
        <div>
            <div>
                <div><label for="location">Location</label></div>
                <Select  v-model="formData.location_id"
                         :disabled="formData.id != ''"
                         @on-change="onChangeLocation"
                         placeholder="Please select a location..."
                >
                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
            <div class="note-label">
                <div><label for="noteType">Note Type</label></div>
                <Select  v-model="formData.note_type_id"
                         :disabled="formData.id != ''"
                         @on-change="onChangeNoteType"
                         placeholder="Please select a note type..."
                >
                    <Option v-for="item in noteTypeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
            <bs-input label="Action (SQL Statement)" type="textarea" :maxlength="10240" v-model="formData.action"></bs-input>
            <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'

    export default {
        props: {
            itemData: {
                type: Object,
                default: null,
            },
            locationOptions: {
                type: Array,
                default: [],
            },
            noteTypeOptions: {
                type: Array,
                default: [],
            },
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                formData: {
                    id: '',
                    location_id: '',
                    note_type_id: '',
                    action: '',
                    comment: '',
                },
            }
        },
        created() {
            console.log('NoteTypeActionBody Component created.');
            if (this.isEmpty(this.itemData))
            {
                this.formData = {
                    id: '',
                    location_id: '',
                    note_type_id: '',
                    action: '',
                    comment: '',
                };
            }
            else
            {
                this.formData = {
                    id: this.itemData.id,
                    location_id: parseInt(this.itemData.location_id),
                    note_type_id: this.itemData.note_type_id,
                    action: this.itemData.action,
                    comment: this.itemData.comment,
                };
            }
        },
        components: {
            'bs-input': input,
        },
        mounted() {
            console.log('NoteTypeActionBody Component mounted.')
        },
        methods: {
            onNewNoteType() {
                console.log('onNewNoteType');
            },
            onChangeLocation(val) {
                console.log('onChangeLocation val=', val);
            },
            onChangeNoteType(val) {
                console.log('onChangeNoteType val=', val);
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
    .note-label {
        margin-bottom: 5px;
    }
</style>
