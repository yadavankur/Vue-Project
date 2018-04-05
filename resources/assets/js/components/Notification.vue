<template>
    <div class="notification">
        <transition name="fade" mode="in-out">
            <bs-alert id="ok" :value="success? true: false"  @input="onInput" placement="top" :duration="duration" type="success" width="400px"  dismissable>
                <span class="glyphicon glyphicon-ok"></span>
                <strong>Success</strong>
                <p>{{ success }}</p>
            </bs-alert>
        </transition>
        <transition name="fade" mode="in-out">
            <bs-alert id="err" :value="error? true: false" @input="onInput"  placement="top"  :duration="duration"  type="danger" width="400px"  dismissable>
                <span class="glyphicon glyphicon-alert"></span>
                <strong>Warning</strong>
                <p>{{ error }}</p>
            </bs-alert>
        </transition>
        <transition name="fade" mode="in-out">
            <bs-alert id="information" :value="info? true: false" @input="onInput" :duration="duration"  placement="top" type="info" width="400px"  dismissable >
                <span class="glyphicon glyphicon-info-sign"></span>
                <strong>Information</strong>
                <p>{{ info }}</p>
            </bs-alert>
        </transition>
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex';
    import alert from 'vue-strap/src/Alert'
    export default {
        data() {  return { duration : 3000,}   },
        components: { 'bs-alert': alert,   },
        computed: {  ...mapState({
                                  success: state => state.notification.success,
                                  error: state => state.notification.error,
                                  info: state => state.notification.info
                               })
                 },
        methods: {
            ...mapActions([
                'hideSuccessNotification',
                'hideErrorNotification',
                'hideInfoNotification'
            ]),
            onInput(val) 
            {  console.log('/js/components/notivation.vue--onInput val=', val);
                if (!val)
                {   this.hideSuccessNotification();
                    this.hideErrorNotification();
                    this.hideInfoNotification();
                }
            }
        }
    }
</script>
<style>
    /**
        make sure the notification is on the top
        9998 is for spinner
        9999 is for notification
     */
    .alert.top {
        z-index: 9999 !important;
    }
</style>