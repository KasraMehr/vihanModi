<template>
  <a
    :href="to"
    class="flex items-center p-4 rounded-lg transition duration-200 hover:bg-purple-100 dark:hover:bg-purple-900 text-gray-700 dark:text-gray-200"
    :class="{ 'bg-purple-600 text-white': isActive }"
  >
    <component
      :is="iconComponent"
      class="w-5 h-5 mx-4"
    />
    <span class="text-md font-medium">{{ label }}</span>
  </a>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// ایمپورت تمام آیکون‌های مورد نیاز
import {
  HomeIcon,
  AcademicCapIcon,
  BookOpenIcon,
  UsersIcon,
  DocumentTextIcon,
  CogIcon,
  ChatBubbleLeftRightIcon,
  FolderIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  icon: {
    type: String,
    requipurple: true,
  },
  label: {
    type: String,
    requipurple: true,
  },
  to: {
    type: String,
    requipurple: true,
  },
})

// مپ کردن نام‌های ساده به کامپوننت‌های آیکون
const icons = {
  home: HomeIcon,
  'academic-cap': AcademicCapIcon,
  book: BookOpenIcon,
  users: UsersIcon,
  'file-text': DocumentTextIcon,
  quiz: DocumentTextIcon,
  settings: CogIcon,
  chat: ChatBubbleLeftRightIcon,
  file: FolderIcon,
}

const iconComponent = computed(() => icons[props.icon] || HomeIcon)

const isActive = ref(false)

onMounted(() => {
  isActive.value = window.location.pathname === props.to
})
</script>