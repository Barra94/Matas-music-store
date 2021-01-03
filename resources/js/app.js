
require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import routes from './routes'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faPlay } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import { store } from './store/store'

library.add(faPlay)

Vue.component('font-awesome-icon', FontAwesomeIcon)




//add is as plugin
Vue.use(VueRouter);




// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

export const eventBus = new Vue(
    {
        methods:{
            changeApiToken(api_token)
            {
                this.$emit('TokenWasChanged',api_token);
            },
            changeSong()
            {
                this.$emit('SongWasChanged');
            }
        }

    }
);


const app = new Vue({
    el: '#app',
    store,
    router: new VueRouter(routes),
    render: h => h(App)
});


