<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="State">State</label></div>
                        <Select clearable filterable labelInValue v-model="formData.state.id"
                                @on-change="OnChanged"
                                size="large"
                                placeholder="Please select a state..."
                        >
                            <Option v-for="item in stateOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <bs-input label="Location Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Abbreviation" type="text" required  :maxlength="255" :icon="true" v-model="formData.abbreviation"></bs-input>
                    <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-success" @click="onClose">Cancel</button>
                <button type="button" class="btn btn-primary" @click="OnSave">Save</button>
            </div>
        </custom-modal>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import input from 'vue-strap/src/Input'

    export default 
    {   computed: {
            ...mapState({ showModal: state => state.location.showModal,
                         locationData: state=> state.location.locationData,
                       }),
                  },
        props: { selectedState: ''  },
        data () {  return {     title: '',
                                formData: {   name: '', abbreviation: '',  comment: '', 
                                            id: '', state_id: '', state: {name:'', id:''}  
                                        },
                                defaultState: '',
                                stateOptions: [],
                                minSearch: 1,
                           }
                },
        created() {  console.log('/settings/location/crud-- created.');
                     this.$store.dispatch('getStateOptions')  // get state list
                     .then((response) => {
                    console.log('/settings/location/crud--created getStateOptions success=', response);
                    this.setStateOptions(response.data.states);
                })
                .catch((error) => {
                    console.error('/settings/location/crud--getStateOptions error=', error);
                });
        },
        components: { 'custom-modal': modal,  'bs-input': input,  },
        mounted() {  console.log('/settings/location/crud--CustomModal Component mounted. locationData=', this.locationData)    },
        methods: 
        {   OnChanged(selVal) 
                {    console.log('/settings/location/crud--OnChanged selVal=', selVal);
                     if (!selVal) return;
                      this.formData.state.name = selVal.label;
                },
            setStateOptions(states) 
                {   console.log('/settings/location/crud--setStateOptions states=',states);
                    let options = [];
                    for (let state in states) { options.push({value: states[state].id, label: states[state].name});  }
                    this.stateOptions = options;
                    if (options.length == 0) return;
                    this.defaultState = this.selectedState? this.selectedState: this.stateOptions[0].value;
                },
            OnSave() 
                {   console.log('/settings/location/crud--OnSave');
                    let payload = {  isShow: false,  data: this.formData, };
                    if (this.locationData.action === 'Add')// new tab
                    {   this.$store.dispatch('setLocationShowModal', payload);
                        this.$store.dispatch('addLocationRequest', this.formData)
                        .then((response) => {  console.log('/settings/location/crud--addLocationRequest fire events -> refreshLocationTable');
                                               this.$events.fire('refreshLocationTable');
                                            })
                        .catch((error) => {});
                    }
                else if (this.locationData.action === 'Edit')
                    {   this.$store.dispatch('setLocationShowModal', payload);
                        this.$store.dispatch('updateLocationRequest', this.formData)
                        .then((response) => {  console.log('/settings/location/crud--updateLocationRequest fire events -> refreshLocationTable');
                                               this.$events.fire('refreshLocationTable');
                                            })
                        .catch((error) => {});
                    }
                else  {     }// error
            },
            onClose() 
            {    console.log('/settings/location/crud--onClose');
                           let payload = { isShow: false, data: this.formData, };
                           this.$store.dispatch('setLocationShowModal', payload);
                           this.resetFormData();
                      },
            resetFormData() {  this.formData = { name: '',  abbreviation: '', comment: '', id: '',  state_id: '',
                                                 state: {name:'', id:''},
                                               };
                            }
        },
        watch: 
        {   locationData() 
            {   console.log('/settings/location/crud--+++++++++++++ locationData changed =', this.locationData);
                if (this.locationData && this.locationData.action === 'Add')
                {   this.resetFormData(); this.title = 'Adding a new location'; }
                else if (this.locationData && this.locationData.action === 'Edit')
                {   this.resetFormData();
                    this.title = 'Editing the location';
                    this.formData.id = this.locationData.data.id;
                    this.formData.name = this.locationData.data.name;
                    this.formData.abbreviation = this.locationData.data.abbreviation;
                    this.formData.comment = this.locationData.data.comment;
                    this.formData.state_id = this.locationData.data.state.id;
                    this.formData.state = this.locationData.data.state;
                }
            }
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
</style>
