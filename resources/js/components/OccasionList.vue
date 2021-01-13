<template>
    <div class="vehicles">
        <template v-for="item of items('sale')" :key="item.getAttribute('id')">

            <div class="vehicle">
                <div v-if="image(item)" class="image">
                    <img :src="image(item)"/>
                </div>

                <span>{{ field(item, 'manufacturer') ?? 'n/a' }}</span><br/>
                <strong>{{ field(item, 'model') ?? 'n/a' }}</strong><br/>

                <table>
                    <tr>
                        <td>Länge:</td>
                        <td>
                            <strong>{{ field(item, 'length') ?? 'n/a' }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Breite:</td>
                        <td>
                            <strong>{{ field(item, 'exterior_width') ?? 'n/a' }}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td>Höhe:</td>
                        <td>
                            <strong>{{ field(item, 'exterior_height') ?? 'n/a' }}</strong>
                        </td>
                    </tr>
                </table>

                <br/>
                <strong>Preis:</strong>
                <span>{{ field(item, 'price') ?? 'n/a' }}</span>
            </div>
        </template>
    </div>
</template>

<script>
import {ref} from 'vue'
import axios from 'axios'

const http = axios.create({
    baseURL: 'https://steinerswohnmobile.dev.localhost/api/'
})

export default {
    setup(props) {
        const xml = ref(null)

        http.get('/occasion')
            .then(res => res.data)
            .then(data => xml.value = (new DOMParser).parseFromString(data, 'application/xml'))

        const items = (type = undefined) => {
            if (!xml.value) {
                return [];
            }

            if (type) {
                return Array.from(xml.value.querySelectorAll(`ad[type=${type}]`));
            } else {
                return Array.from(xml.value.querySelectorAll('ad'));
            }
        }


        return {
            // Methods
            items,
            field: (vehicle, id) => vehicle.querySelector('field[id=' + id + ']')?.textContent,
            image: (vehicle) => vehicle.querySelector('field[id=thumbnail]')?.textContent
        }
    }
}
</script>

<style lang="scss">
.vehicle {
    display: inline-block;
    width: 300px;
    background: #fbfbfbc2;
    padding: 0 20px 20px 20px;
    margin: 10px;
    border: 1px solid #d6d6d675;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 0 10px 0px #2535511a;

    .image {
        width: calc(100% + 42px);
        height: 0;
        padding-top: 75%;
        position: relative;
        overflow: hidden;
        margin: -1px 0 20px -21px;

        img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: auto;
        }
    }
}
</style>
