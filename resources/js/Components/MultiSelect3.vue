<template>
    <div>
        <multiselect
            v-model="selected"
            :options="options"
            :multiple="true"
            :close-on-select="false"
            :clear-on-select="false"
            :preserve-search="true"
            placeholder="Pick some"
            label="name"
            track-by="name"
            :preselect-first="true"
        >
        </multiselect>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
    components: { Multiselect },
    data() {
        return {
            selected: null,
            options: ["list", "of", "options"],
        };
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
