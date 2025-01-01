<template>
    <div>
        <Multiselect v-model="value" :options="options" />
    </div>
</template>

<script>
import Multiselect from "@vueform/multiselect";

export default {
    components: {
        Multiselect,
    },
    data() {
        return {
            value: null,
            options: ["Batman", "Robin", "Joker"],
        };
    },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
