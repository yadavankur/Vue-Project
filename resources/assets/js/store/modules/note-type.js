import Vue from 'vue';
import * as api from './../../config';

export default {
    state: {
    },
    mutations: {
    },
    actions: {
        updateNoteTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/note-type.js-updateNoteTypeRequest formData=', formData);
                Vue.http.post(api.updateNoteType, formData)
                    .then(response => {
                        let actionType = formData.type;
                        dispatch('showSuccessNotification', 'Note Type has been ' + actionType + '.');
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('showSuccessNotification', response.body.error);
                        reject(response);
                    });
            })
        },
        updateNoteTypeActionRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/note-type.js-updateNoteTypeActionRequest formData=', formData);
                Vue.http.post(api.updateNoteTypeAction, formData)
                    .then(response => {
                        let actionType = formData.type;
                        dispatch('showSuccessNotification', 'Note Type Action has been ' + actionType + '.');
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('showSuccessNotification', response.body.error);
                        reject(response);
                    });
            })
        },
        getNoteTypeOptions: () => {
            console.log('/resources/assets/js/store/modules/note-type.js-getNoteTypeOptions');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getNoteTypeOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/note-type.js-getNoteTypeOptions response.body=', response.body);
                        reject(response);
                    });
            })
        },
        getDowellNoteTypeOptions: () => {
            console.log('/resources/assets/js/store/modules/note-type.js-getDowellNoteTypeOptions');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getDowellNoteTypeOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/note-type.js-getDowellNoteTypeOptions response.body=', response.body);
                        reject(response);
                    });
            })
        },
    }
}