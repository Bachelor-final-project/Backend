<script setup>
import { toast } from "vue3-toastify";
import { computed, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
const page = usePage();
const res = computed(() => page.props.flash);
function run() {
  console.log("Altert Run function: ");
  if (res && res.value && res.value.message && res.value.type) {
    console.log("Altert Run function Conedestions met");
    toast[res.value.type](res.value.message, {
      position: getPosition(),
    });
  }
}
function getPosition() {
  if (page.url.includes("forms")) {
    return page.props.locale == "ar"
      ? toast.POSITION.BOTTOM_RIGHT
      : toast.POSITION.BOTTOM_LEFT;
  } else {
    return page.props.locale == "ar"
      ? toast.POSITION.BOTTOM_LEFT
      : toast.POSITION.BOTTOM_RIGHT;
  }
}
watch(
  res,
  (val, old) => {
    run();
  },
  { deep: true }
);
</script>

<style></style>
