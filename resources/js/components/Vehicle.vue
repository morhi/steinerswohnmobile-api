<template>
    <div :class="['vehicle', classes]" ref="el">
        <div class="image-slider">
            <x-slider @open="() => $emit('lightbox', images().map(i => i.textContent))"
                      :images="images().map(i => i.textContent)"/>
        </div>

        <div class="image-list">
            <div class="thumbnail" v-for="(image, index) of images().slice(1, 5)">
                <img
                    :src="image.textContent"
                    width="150"
                    @click="() => $emit('lightbox', images().map(i => i.textContent), index + 1)"
                />
            </div>

            <div class="more-images"
                 v-if="images().length > 4"
                 @click="() => $emit('lightbox', images().map(i => i.textContent), 5)"
            ></div>
        </div>

        <div class="data">
            <div class="title">{{ field('title') }}</div>

            <div class="attribute-list">
                <div class="attribute" v-if="field('first_registration')">
                    {{ label('first_registration') }} {{ field('first_registration') }}
                </div>

                <div class="attribute" v-if="field('mileage')">
                    {{ field('mileage') }}
                </div>
            </div>

            <div class="attribute-list">
                <div class="attribute">{{ field('engine_power')?.toUpperCase() }}</div>
                <div class="attribute">{{ field('gearbox') }}</div>
            </div>

            <div class="attribute-list">
                <div class="attribute">
                    {{ label('length') }}: {{ field('length') }}
                </div>

                <div class="attribute">
                    {{ label('exterior_width') }}: {{ field('exterior_width') }}
                </div>

                <div class="attribute">
                    {{ label('exterior_height') }}: {{ field('exterior_height') }}
                </div>
            </div>

            <div class="attribute-list">
                <div class="attribute" v-if="field('passenger_capacity')">
                    {{ field('passenger_capacity') }} {{ label('passenger_capacity') }}
                </div>

                <div class="attribute" v-if="field('sleeping_berths')">
                    {{ field('sleeping_berths') }} {{ label('sleeping_berths') }}
                </div>
            </div>
        </div>

        <div class="price" v-if="type === 'sale'">
            {{ field('price') }}
        </div>

        <div class="price" v-if="type === 'rent'">
            {{ data.rent_price }}
        </div>

        <div class="actions">
            <a
                :href="pdfLink()"
                target="_blank"
                class="sqs-block-button-element"
            >Details ansehen</a>

            <a
                :href="contactLink()"
                class="sqs-block-button-element"
            >Kontakt aufnehmen</a>
        </div>
    </div>
</template>

<script>
import { ResponsiveWidth } from "../compositions/ResponsiveWidth";
import Slider from "./Slider";
import { onMounted, ref } from "vue";
import { api } from "../api";

export default {
    components: {
        'x-slider': Slider
    },
    props: {
        vehicle: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const { vehicle } = props;
        const { el, classes } = ResponsiveWidth();

        const id = vehicle.getAttribute('id');
        const type = vehicle.getAttribute('type');
        const field = (id, fallback = null) => vehicle.querySelector('field[id=' + id + ']')?.textContent ?? fallback;

        const data = ref({});

        const getVehicleData = async () => {
            data.value = await api.getVehicle(id)
        }

        onMounted(getVehicleData);

        return {
            el,
            classes,
            id,
            type,
            data,

            // Methods
            field,
            label: (id) => vehicle.querySelector('field[id=' + id + ']')?.getAttribute('label') ?? null,
            image: () => vehicle.querySelector('field[id=thumbnail]')?.textContent,
            images: () => Array.from(vehicle.querySelectorAll('advert_media media') ?? []),

            // Getters
            pdfLink: () => `https://www.caravan24.ch/pdf/ins/${ vehicle.getAttribute('id') }.chde.pdf`,
            contactLink: () => {
                const subject = 'Anfrage zu ' + field('title')

                return `mailto:info@steiners-wohnmobile.ch?subject=${ encodeURIComponent(subject) }`
            },
        }
    }
}
</script>

<style lang="scss" scoped>
* {
    box-sizing: border-box;
}

.vehicle {
    display: flex;
    flex-direction: column;
    //flex: 1;
    //min-width: 280px;
    //max-width: 500px;
    //width: 100%;
    background: #fbfbfbc2;
    padding: 0 20px 20px 20px;
    //margin: 10px;
    border: 1px solid #d6d6d675;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 0 5px 0px #2535511f;

    .image-slider {
        width: calc(100% + 42px);
        margin: -1px 0 20px -21px;

        &:hover::v-deep img {
            transform: scale(1.02);
        }
    }

    /**
    .image {
        width: calc(100% + 42px);
        height: 0;
        padding-top: 60%;
        position: relative;
        overflow: hidden;
        margin: -1px 0 20px -21px;
        cursor: pointer;

        &:hover img {
            transform: scale(1.02);
        }

        img {
            transition: transform .2s ease;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
    **/

    .image-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        row-gap: 10px;
        column-gap: 10px;
        margin: 0 0 10px 0;

        .thumbnail {
            display: flex;
            flex: 1;
            min-width: 50px;
            height: 50px;
            overflow: hidden;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 3px;

            &:hover img {
                transform: scale(1.1);
            }

            img {
                transition: transform .2s ease;
                max-width: unset;
            }
        }

        .more-images {
            height: 50px;
            width: 50px;
            padding: 20px 25px;
            cursor: pointer;
            border-radius: 3px;

            background: linear-gradient(#777, #777), linear-gradient(#777, #777), #eee;
            background-position: center;
            background-repeat: no-repeat;
            background-size: 50% 2px, 2px 50%; /*thickness = 2px, length = 50% (25px)*/
        }
    }

    .data {
        text-align: center;
        font-size: 1rem;
        flex: 1;

        .title {
            font-weight: 500;
            font-size: calc((1.6 - 1) * 1.2vh + 1rem);
            line-height: 1.3664;
            margin: 1em 0;

            @media screen and (min-width: 1944px) {
                font-size: calc(1.6 * 1rem);
            }
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

    .price {
        text-align: center;
        font-weight: 500;
        font-size: 1.5em;
        margin-top: 20px;
    }

    .actions {
        margin-top: 20px;
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

    &.small {
        .sqs-block-button-element {
            font-size: .85rem;
            padding: 1rem 2rem;
        }
    }
}
</style>
