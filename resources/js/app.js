import * as Vue from 'vue';
import Lightbox from 'vue-easy-lightbox'

import OccasionList from './components/OccasionList'

const app = Vue.createApp(OccasionList)

app.use(Lightbox)
app.mount('#occasion-list');
