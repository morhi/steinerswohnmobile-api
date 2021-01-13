<template>
    <div>
        Halloooo: {{ counter }}

        <button @click="addCounter">Mehr</button>
    </div>
</template>

<script>
import {ref} from 'vue'

export default {
    setup(props) {
        const counter = ref(0)

        return {
            counter,
            addCounter: () => counter.value = counter.value + 1
        }
    }
}
</script>
