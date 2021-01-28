<template>
    <div class="slider">
        <div @click="$emit('open')" class="slide" v-for="i in [currentIndex]" :key='i'>
            <img :src="currentImg"/>
        </div>

        <div class="prev" @click="prev">&#10094;</div>
        <div class="next" @click="next">&#10095;</div>
    </div>
</template>

<script>
export default {
    name: 'VueImageSlider',
    data() {
        return {
            timer: null,
            currentIndex: 0,
        }
    },
    props: {
        images: Array,
        height: {
            type: Number,
            default: 400
        },
        width: {
            type: Number,
            default: 700
        }
    },

    methods: {
        next: function () {
            this.currentIndex += 1
        },
        prev: function () {
            this.currentIndex -= 1
        }
    },

    computed: {
        currentImg: function () {
            return this.images[Math.abs(this.currentIndex) % this.images.length];
        },
        imgSize() {
            return {
                height: `${ this.height }px`,
                width: `${ this.width }px`,
            };
        }
    },
}
</script>

<style lang="scss" scoped>
* {
    box-sizing: border-box;
}

.slider {
    position: relative;
    width: 100%;

    .slide {
        width: 100%;
        height: 0;
        padding-top: 60%;
        position: relative;
        overflow: hidden;
        cursor: pointer;

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

    .fade-enter-active, .fade-leave-active {
        transition: all 0.9s ease;
        overflow: hidden;
        visibility: visible;
        position: absolute;
        width: 100%;
        opacity: 1;
    }

    .fade-enter, .fade-leave-to {
        visibility: hidden;
        width: 100%;
        opacity: 0;
    }

    .prev, .next {
        cursor: pointer;
        position: absolute;
        top: calc(50% - 32px);
        height: 64px;
        width: auto;
        padding: 16px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.7s ease;
        text-decoration: none;
        user-select: none;
    }

    .next {
        right: 0;
        border-radius: 4px 0 0 4px;
    }

    .prev {
        left: 0;
        border-radius: 0 4px 4px 0;
    }

    .prev:hover, .next:hover {
        background-color: rgba(0, 0, 0, 0.6);
    }
}

</style>
