<template>
  <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden h-96 min-h-96" style="perspective: 1000px;">
    <div 
      class="relative w-full h-full transition-transform duration-700 preserve-3d cursor-pointer"
      :class="{ 'rotate-y-180': isFlipped }"
      @click="toggleFlip"
    >
      <!-- Front Side -->
      <div class="absolute inset-0 backface-hidden flex flex-col">
    <!-- Project Image -->
    <div class="relative overflow-hidden">
      <img
        :src="imageSrc"
        :alt="proposal.title"
        class="w-full h-48 object-cover rounded-t-lg"
        loading="lazy"  
      />
    </div>

    <div class="p-4 flex-1 flex flex-col">
      <!-- Project Title -->
      <h3 class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 mb-2 rtl:text-right">
        {{ proposal.title }}
      </h3>

      

      <!-- Progress Section -->
      <div class="mb-4">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ progressPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
          <div 
            ref="progressBar"
            class="bg-blue-600 h-2 rounded-full transition-all duration-1000 ease-out" 
            :style="`width: ${animatedProgress}%`"
          ></div>
        </div>
      </div>

      <!-- Numbers Row -->
      <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-4 rtl:flex-row-reverse">
        <span>{{ $t("Raised") }}: {{ proposal.currency_code }} {{ formatNumber(proposal.paid_amount) }}</span>
        <span>{{ $t("Goal") }}: {{ proposal.currency_code }} {{ formatNumber(proposal.cost) }}</span>
      </div>

      <!-- Donation Section -->
      <div class="mt-auto">
        <!-- Preset Amount Buttons -->
        <div class="grid grid-cols-3 gap-2 mb-3">
          <button
            v-for="amount in presetAmounts"
            :key="amount"
            @click="selectAmount(amount)"
            :class="[
              'py-2 px-3 text-sm font-medium rounded-md border transition-colors',
              selectedAmount === amount
                ? 'bg-blue-600 text-white border-blue-600'
                : 'bg-gray-50 text-gray-700 border-gray-300 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
            ]"
          >
            {{ amount }}
          </button>
        </div>

        <!-- Custom Amount Input -->
        <div class="mb-3">
          <TextInput
            :id="`donationAmount_${proposal.id}`"
            type="number"
            v-model="customAmount"
            :placeholder="$t('Custom amount')"
            class="w-full text-sm"
            @input="handleCustomInput"
            @click="() => isFlipped = true"
          />
        </div>
        
        <!-- Online Payment Option -->
        <div v-if="proposal.isPayableOnline" class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
          <span class="text-xs text-gray-500 dark:text-gray-400">{{ $t("pay online") }}</span>
          <Checkbox v-model="payonlineChecked" @update:checked="emitDonation" />
        </div>
      </div>
    </div>
      </div>

      <!-- Back Side -->
      <div class="absolute inset-0 backface-hidden rotate-y-180 flex flex-col p-6 bg-white dark:bg-gray-800 rounded-lg">
        <div class="flex-1 flex flex-col justify-center ">
          <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4 rtl:text-right">
            {{ proposal.title }}
          </h3>
          <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed rtl:text-right overflow-y-auto">
            {{ proposal.body }}
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from './Checkbox.vue';

const props = defineProps({
  proposal: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['donate']);

const imageSrc = ref(props.proposal.cover_image || new URL(`@/assets/images/hero.jpg`, import.meta.url).href);
const selectedAmount = ref(null);
const customAmount = ref('');
const payonlineChecked = ref(false);
const animatedProgress = ref(0);
const progressBar = ref(null);
const isFlipped = ref(false);

const presetAmounts = [50, 100, 250];

const progressPercentage = computed(() => {
  return Math.min(Math.round((props.proposal.paid_amount / props.proposal.cost) * 100), 100);
});

const currentAmount = computed(() => {
  return selectedAmount.value || parseFloat(customAmount.value) || 0;
});

const isValidAmount = computed(() => {
  return currentAmount.value >= props.proposal.min_documenting_amount;
});

const selectAmount = (amount) => {
  selectedAmount.value = amount;
  customAmount.value = '';
  isFlipped.value = true;
};

const handleCustomInput = () => {
  selectedAmount.value = null;
};

const handleDonate = () => {
  if (isValidAmount.value) {
    const amount = Math.round(currentAmount.value);
    emit('donate', props.proposal.id, amount, props.proposal.currency_id, payonlineChecked.value, props.proposal.min_documenting_amount);
  }
};

const emitDonation = () => {
  handleDonate();
};

const formatNumber = (num) => {
  return new Intl.NumberFormat().format(num);
};

const toggleFlip = () => {
  isFlipped.value = !isFlipped.value;
};

// Animate progress bar on mount
onMounted(async () => {
  await nextTick();
  setTimeout(() => {
    animatedProgress.value = progressPercentage.value;
  }, 100);
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.preserve-3d {
  transform-style: preserve-3d;
}

.backface-hidden {
  backface-visibility: hidden;
}

.rotate-y-180 {
  transform: rotateY(180deg);
}
</style>