<template>
    <div>
        <div>
            <bs-input label="Name " type="text" :maxlength="255" v-model="formData.name"></bs-input>
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
            }
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
                    name: '',
                    comment: '',
                },
            }
        },
        created() {
            console.log('NoteTypeBody Component created.');
            if (this.isEmpty(this.itemData))
            {
                this.formData = {
                    id: '',
                    name: '',
                    comment: '',
                };
            }
            else
            {
                this.formData = {
                    id: this.itemData.id,
                    name: this.itemData.name,
                    comment: this.itemData.comment,
                };
            }
        },
        components: {
            'bs-input': input,
        },
        mounted() {
            console.log('NoteTypeBody Component mounted.')
        },
        methods: {
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
