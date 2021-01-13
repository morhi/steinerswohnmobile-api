<template>
    <div class="vehicles">
        <vue-easy-lightbox
            :imgs="lightbox.state.images"
            :visible="lightbox.state.visible"
            :index="lightbox.state.index"
            @hide="lightbox.hide"
        ></vue-easy-lightbox>

        <template v-for="item of items('sale')" :key="item.getAttribute('id')">

            <div class="vehicle">
                <div class="image">
                    <img
                        v-if="image(item)"
                        :src="images(item)[0].textContent"
                        @click="() => lightbox.open(images(item).map(i => i.textContent))"
                    />
                </div>

                <div class="image-list">
                    <div class="thumbnail" v-for="(image, index) of images(item).slice(1, 5)">
                        <img
                            :src="image.textContent"
                            width="150"
                            @click="() => lightbox.open(images(item).map(i => i.textContent), index + 1)"
                        />
                    </div>
                </div>

                <div class="data">
                    <div class="title">{{ field(item, 'title') }}</div>

                    <div class="attribute-list">
                        <div class="attribute">
                            {{ label(item, 'first_registration') }} {{ field(item, 'first_registration') }}
                        </div>

                        <div class="attribute">{{ field(item, 'mileage') }}</div>
                    </div>

                    <div class="attribute-list">
                        <div class="attribute">{{ field(item, 'engine_power') }}</div>
                        <div class="attribute">{{ field(item, 'gearbox') }}</div>
                    </div>

                    <div class="attribute-list">
                        <div class="attribute">
                            {{ label(item, 'length') }} {{ field(item, 'length') }}
                        </div>

                        <div class="attribute">
                            {{ label(item, 'exterior_width') }} {{ field(item, 'exterior_width') }}
                        </div>

                        <div class="attribute">
                            {{ label(item, 'exterior_height') }} {{ field(item, 'exterior_height') }}
                        </div>
                    </div>

                    <div class="attribute-list">
                        <div class="attribute">
                            {{ field(item, 'passenger_capacity') }} {{ label(item, 'passenger_capacity') }}
                        </div>

                        <div class="attribute">
                            {{ field(item, 'sleeping_berths') }} {{ label(item, 'sleeping_berths') }}
                        </div>
                    </div>

                    <div class="price">
                        {{ field(item, 'price') }}
                    </div>
                </div>

                <div class="actions">
                    <a
                        :href="pdfLink(item)"
                        target="_blank"
                        class="sqs-block-button-element"
                        @click="detailClickHandler"
                    >Details ansehen</a>

                    <a
                        :href="contactLink(item)"
                        class="sqs-block-button-element"
                        @click="contactClickHandler"
                    >Kontakt aufnehmen</a>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import {reactive, ref} from 'vue'
import axios from 'axios'

const http = axios.create({
    baseURL: 'https://p576249.mittwaldserver.info/api/'
})

export default {
    setup(props) {
        const xml = ref(null)
        const lightbox = reactive({
            images: [],
            visible :false,
            index: 0
        })

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

        const detailClickHandler = (e) => console.log(e)
        const contactClickHandler = (e) => console.log(e)

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

            // Methods
            items,
            field: (vehicle, id, fallback = null) => vehicle.querySelector('field[id=' + id + ']')?.textContent ?? fallback,
            label: (vehicle, id) => vehicle.querySelector('field[id=' + id + ']')?.getAttribute('label') ?? null,
            image: (vehicle) => vehicle.querySelector('field[id=thumbnail]')?.textContent,
            images: (vehicle) => Array.from(vehicle.querySelectorAll('advert_media media') ?? []),

            // Getters
            pdfLink: (vehicle) => `https://www.caravan24.ch/pdf/ins/${vehicle.getAttribute('id')}.chde.pdf`,
            contactLink: (vehicle) => `mailto:welgkj@test.de?subject=${vehicle.getAttribute('id')}`,

            // Handlers
            detailClickHandler,
            contactClickHandler
        }
    }
}
</script>

<style lang="scss">
.vehicle {
    display: inline-block;
    width: 450px;
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
        padding-top: 60%;
        position: relative;
        overflow: hidden;
        margin: -1px 0 20px -21px;
        cursor: pointer;

        img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    .image-list {
        display: flex;
        column-gap: 10px;
        margin: 0 0 10px 0;

        .thumbnail {
            display: flex;
            height: 50px;
            overflow: hidden;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
    }

    .data {
        text-align: center;
        font-size: .9em;

        .title {
            font-weight: 500;
            font-size: 1.3em;
        }

        .price {
            font-weight: 500;
            font-size: 1.5em;
        }

        .attribute-list .attribute {
            display: inline-block;

            &:empty {
                display: none;
            }

            &:not(:empty):not(:last-of-type):after {
                display: inline;
                content: ',';
                margin-right: 5px;
            }
        }
    }

    .actions {
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        row-gap: 10px;
        text-align: center;
    }

    .sqs-block-button-element {
        cursor: pointer;
        transition: all .1s ease;
        user-select: none;

        &:hover {
            opacity: .9;
            filter: brightness(1.3) saturate(1.2);
        }
    }
}
</style>
