import * as Vue from 'vue';
import Lightbox from 'vue-easy-lightbox'

import VehicleList from './components/VehicleList'

const containers = document.querySelectorAll('[data-vehicles]');

containers.forEach(container => {
    const scope = container.getAttribute('data-vehicles') || 'sale';
    const type = container.getAttribute('data-type');

    const app = Vue.createApp(VehicleList, {
        scope,
        type
    })

    app.use(Lightbox)
    app.mount(container);
})
