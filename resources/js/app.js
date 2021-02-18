import * as Vue from 'vue';
import Lightbox from 'vue-easy-lightbox'

import VehicleList from './components/VehicleList'

const containers = document.querySelectorAll('[data-vehicles]');

containers.forEach(container => {
    const type = container.getAttribute('data-vehicles');

    const app = Vue.createApp(VehicleList, {
        type
    })

    app.use(Lightbox)
    app.mount(container);
})
