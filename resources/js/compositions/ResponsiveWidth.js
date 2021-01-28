import { computed, onMounted, onUnmounted, ref } from "vue";

export const ResponsiveWidth = () => {
    const el = ref(null);
    const width = ref(0);

    const update = () => {
        width.value = el.value.getBoundingClientRect().width;
    }

    const classes = computed(() => {
        if (width.value > 400) {
            return 'large';
        }

        return 'small';
    })

    onMounted(() => {
        window.addEventListener('resize', update);
        update();
    })

    onUnmounted(() => {
        window.removeEventListener('resize', update);
    })

    return {
        el,
        classes,
    }
}
