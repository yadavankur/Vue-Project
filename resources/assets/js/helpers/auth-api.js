import Vue from 'vue';
import * as api from './../config';

export default {
    actions:
    {
        checkPermission: ({dispatch}, data) => {
            return new Promise((resolve, reject) => {
                console.log('/assets/js/helpers/auth-api.js-checkPermission data=',data);
                Vue.http.get(api.checkpermission + '?urlPath=' + data.urlPath)
                    .then(response => {
                        console.log('/assets/js/helpers/auth-api.js-checkPermission response=',response);
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
    }
}