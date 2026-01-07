<template>
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <NewProposalCard
        v-for="proposal in proposals"
        :key="proposal.id"
        :proposal="proposal"
        @donate="handleDonate"
      />
    </div>
  </div>
</template>

<script setup>
import NewProposalCard from './NewProposalCard.vue';

const props = defineProps({
  proposals: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['donate']);

const handleDonate = (proposalId, amount, currencyId, payOnline, minAmount) => {
  emit('donate', proposalId, amount, currencyId, payOnline, minAmount);
};
</script>