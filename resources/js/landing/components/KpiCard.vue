<template>
  <div class="card kpi">
    <div>
      <div class="label">{{ label }}</div>
      <div class="value">{{ value }}</div>
    </div>
    <slot name="icon"></slot>
  </div>
</template>
<script setup>
defineProps({ label: String, value: [String, Number] })
</script>
<style scoped>
.label{ color:#9ca3af }
.value{ font-size:1.75rem; font-weight:700 }
</style>
