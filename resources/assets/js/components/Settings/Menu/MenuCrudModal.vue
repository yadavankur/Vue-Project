<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Menu Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Url Path" type="text" required  :maxlength="255" :icon="true" v-model="formData.link"></bs-input>
                    <bs-input label="Parent Menu Name"  type="text" disabled v-model="formData.parent.name"></bs-input>
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
    {  computed: { ...mapState({  showModal: state => state.menu.showModal,  menuData: state=> state.menu.menuData,  }),
                },
        data () {  return { title: '',
                            formData: 
                                {   name: '', link: '',
                                    parent: {name:'',id: '', link: ''},
                                    comment: '', id: '', parent_id: '',
                                }
                         }
                },
        created() { console.log('/settings/menu/crud.vue-- Component created.') },
        components: {  'custom-modal': modal, 'bs-input': input,   },
        mounted() {  console.log('/settings/menu/crud.vue-- mounted. menuData=', this.menuData)    },
        methods: 
        {   OnSave() {  console.log('/settings/menu/crud.vue--OnSave');
                        let payload = {  isShow: false, data: this.formData,   };
                        if (this.menuData.action === 'Add')
                            {   // new menu
                                this.$store.dispatch('setMenuShowModal', payload);
                                this.$store.dispatch('addMenuRequest', this.formData)
                                .then((response) => {
                                    console.log('/settings/menu/crud.vue--addMenuRequest fire events -> refreshMenuTable');
                                    this.$events.fire('refreshMenuTable');
                                    })
                                .catch((error) => {});
                            }
                        else if (this.menuData.action === 'Edit') // update
                            {   this.$store.dispatch('setMenuShowModal', payload);
                                this.$store.dispatch('updateMenuRequest', this.formData)
                                .then((response) => {
                                    console.log('/settings/menu/crud.vue--updateMenuRequest fire events -> refreshMenuTable');
                                    this.$events.fire('refreshMenuTable');
                                    })
                                .catch((error) => {});
                            }
                         else  { }  // error
               
                      }, //if finished
            onClose() { console.log(' /settings/menu/crud.vue--onClose');
                        let payload = {  isShow: false,  data: this.formData,  };
                        this.$store.dispatch('setMenuShowModal', payload);
                        this.resetFormData();
                       },
            resetFormData() { this.formData = {  name: '',   link: '',
                                                parent: {name:'',id: '', comment:'', parent_id: '', link: ''},
                                                comment: '', id: '',  parent_id: '',
                                            };
                            }
        }, //mounted finish
        watch: 
        {  menuData() 
                {  console.log('/settings/menu/crud.vue-+++++++++++++ menuData changed =', this.menuData);
                   if (this.menuData && this.menuData.action === 'Add')
                    {   this.resetFormData();
                        this.formData.parent_id = this.menuData.data.id;
                        this.formData.parent.name = this.menuData.data.name;
                        this.title = 'Adding a new menu';
                    }
                   else if (this.menuData && this.menuData.action === 'Edit')
                    {   this.resetFormData();
                        this.title = 'Editing the menu';
                        if (this.menuData.data.parent)
                        {  this.formData = JSON.parse(JSON.stringify(this.menuData.data)); }
                        else
                         {  this.formData.id = this.menuData.data.id;
                            this.formData.link = this.menuData.data.link;
                            this.formData.name = this.menuData.data.name;
                            this.formData.comment = this.menuData.data.comment;
                            this.formData.parent_id = this.menuData.data.parent_id;
                         }
                     }
                }
        }  //watch finished
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
