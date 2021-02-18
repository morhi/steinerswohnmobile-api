<template>
    <div class="vehicles sqs-layout">
        <vue-easy-lightbox
            :imgs="lightbox.state.images"
            :visible="lightbox.state.visible"
            :index="lightbox.state.index"
            @hide="lightbox.hide"
        ></vue-easy-lightbox>

        <div class="row sqs-row">
            <div class="col sqs-col-4 span-4" v-for="item of items()" :key="item.getAttribute('id')">
                <div class="sqs-block">
                    <Vehicle :vehicle="item" @lightbox="(images, index) => lightbox.open(images, index)"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref, toRef, toRefs } from 'vue'
import axios from 'axios'

import Vehicle from './Vehicle'

const http = axios.create({
    baseURL: process.env.MIX_API
})

export default {
    components: {
        Vehicle
    },
    props: {
        scope: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            required: true
        }
    },
    setup(props) {
        const scope = toRef(props, 'scope')
        const type = toRef(props, 'type')

        const xml = ref(null)
        const lightbox = reactive({
            images: [],
            visible: false,
            index: 0
        })

        http.get('/vehicles')
            .then(res => res.data)
            .then(data => xml.value = (new DOMParser).parseFromString(data, 'application/xml'))

        const items = () => {
            if (!xml.value) {
                return [];
            }

            return Array
                .from(xml.value.querySelectorAll(`ad[type=${ scope.value }]`))
                .filter(ad => {
                    return ad.querySelector('field[id=saleclass]').textContent === type.value
                })
        }

        return {
            // Lightbox
            lightbox: {
                state: lightbox,
                open: (images, index = 0) => {
                    lightbox.images = images;
                    lightbox.visible = true;
                    lightbox.index = index;
                },
                hide: () => {
                    lightbox.images = [];
                    lightbox.visible = false;
                }
            },

            items,
        }
    }
}
</script>

<style lang="scss" scoped>

* {
    box-sizing: border-box;
}

.vehicles {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;

    @media all and (max-width: 340px) {
        margin-left: -19px;
        width: 100vw;
    }

    > .row {
        display: flex;
        flex-wrap: wrap;

        > .col > .sqs-block {
            height: 100%;

            > .vehicle {
                height: 100%;
            }
        }
    }
}
</style>
