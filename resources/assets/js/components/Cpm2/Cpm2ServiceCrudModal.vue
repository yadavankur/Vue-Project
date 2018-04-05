<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header"><h4 class="modal-title"> {{ title }} </h4></div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group"> <div class="form-group">
                        <div><label for="location">Location</label></div> <!--used while adding new record -->
                        <Cascader v-model="cascade_state_location" :data="cascadeLocationOptions"
                                  size="large"  placeholder="Please select a state/location..."
                                  @on-change="handleChange" :disabled="formData.id != ''"
                        > </Cascader>
                    </div>
                    <bs-input label="Service Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name" :disabled="formData.id != ''"></bs-input>
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
    {  computed: { ...mapState({ showModal: state => state.cpm2.showModal, cpmServiceData: state=> state.cpm2.cpm2ServiceData,  }),   },
       props:    {  },
       data ()   { return {  title: '',
                              formData: {  name: '', comment: '', id: '', location_id: '',location: {name:'', id:''},state: {name:'', id:''},},
                              cascadeLocationOptions: [],
                              cascade_state_location: [],
                           }
                  },
        created() 
        {   console.log('/cpm2/crud--CustomModal Component created.');
            this.$store.dispatch('getAssignedCascadeLocationOptions')  // get location list
                .then((response) => 
                {   console.log('/cpm2/crud.vue created getAssignedCascadeLocationOptions success=', response);
                    this.cascadeLocationOptions = response.data.cascadeLocations;
                })
                .catch((error) => { console.error('/cpm2/crud.vue--getAssignedCascadeLocationOptions error=', error);  });
        },
        components: {   'custom-modal': modal,   'bs-input': input,   },
        mounted() {  console.log('/Cpm2/CRUD mounted. cpmServiceData=', this.cpm2ServiceData)  },
        methods: 
          { handleChange (value, selectedData) //----used in add---location dropdown
              {  console.log('/cpm2/crud.vue--handleChange value=', value);
                 console.log('/cpm2/crud.vue--handleChange selectedData=', selectedData);
                 this.formData.location = {id:'', name:''};
                 this.formData.state = {id:'', name:''};
                 if (selectedData.length > 0)
                   {  this.formData.state = {id: selectedData[0].value, name: selectedData[0].label};
                      this.formData.location = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                   }
              },
            OnSave() 
            {   console.log('/cpm2/crud.vue--OnSave clicked');
                let payload = { isShow: false, data: this.formData, };
                if (this.cpmServiceData.action === 'Add')
                   {   this.$store.dispatch('setCpm2ServiceShowModal', payload);
                       this.$store.dispatch('addCpm2ServiceRequest', this.formData)
                        .then((response) => {console.log('/cpm2/crud.vue--add->refresh'); 
                        this.$events.fire('refreshCpm2ServiceTable'); })
                        .catch((error) => {});

                   }
                else if (this.cpmServiceData.action === 'Edit')
                   {   this.$store.dispatch('setCpm2ServiceShowModal', payload);
                       this.$store.dispatch('updateCpm2ServiceRequest', this.formData)
                        .then((response) => { console.log('/cpm2/crud.vue--update->refresh'); this.$events.fire('refreshCpm2ServiceTable'); })
                        .catch((error) => {});
                   }
                else
                { } // if error
            },
            onClose() 
               { console.log('/cpm2/crud.vue--onClose');
                 let payload = { isShow: false, data: this.formData, };
                 this.$store.dispatch('setCpm2ServiceShowModal', payload);
                 this.resetFormData();
                },
            resetFormData() 
               {    this.formData = { name: '', comment: '', id: '', location_id: '',
                                      location: {name:'', id:''}, state: {name:'', id:''},
                                    };
                    this.cascade_state_location = [];
                }
        },
        watch: 
         {  cpmServiceData() 
              { console.log('/cpm2/crud.vue--+++ cpmServiceData changed =', this.cpm2ServiceData);
                if (this.cpmServiceData && this.cpmServiceData.action === 'Add') //----------add new cpm
                    { this.resetFormData();    this.title = 'Adding a new CPM2 service';  }
                else if (this.cpmServiceData && this.cpmServiceData.action === 'Edit')
                    { this.resetFormData();
                      this.title = 'Editing the CPM2 service';
                      this.formData.id = this.cpmServiceData.data.id;
                      this.formData.name = this.cpmServiceData.data.name;
                      this.formData.comment = this.cpmServiceData.data.comment;
                      this.formData.location_id = this.cpmServiceData.data.location.id;
                      this.formData.location.id = this.cpmServiceData.data.location.id;
                      this.formData.location.name = this.cpmServiceData.data.location.name;
                      this.formData.state.id = this.cpmServiceData.data.location.state.id;
                      this.formData.state.name = this.cpmServiceData.data.location.state.name;
                      this.cascade_state_location.push(this.formData.state.id);
                      this.cascade_state_location.push(this.formData.location.id);
                   }//if eleseif finished
              }//cpmServiceData is finished
        }
    }
</script>

<style scoped>
</style>
