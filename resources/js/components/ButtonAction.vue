<template>
  <div class="dropdown">
    <div v-if="type == 'click'" @click="onEvent" v-click-outside="onClose" id="button_action">
      <div>
        <!-- <svg
          xmlns="http://www.w3.org/2000/svg"
          width="10"
          height="10"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="feather feather-more-vertical"
        >
          <circle cx="12" cy="12" r="1" />
          <circle cx="12" cy="5" r="1" />
          <circle cx="12" cy="19" r="1" />
        </svg>-->
        <span class="cursor-pointer">
          <!-- <i class="fas fa-edit" style="color: #31D2F2;"></i> -->
          <i class="bi bi-pencil" style="color: #31D2F2;"></i>
        </span>
      </div>
      <div v-if="is_show_content" class="dropdown-content">
        <slot name="list_detail_button"></slot>
      </div>
    </div>
    <div v-else-if="type == 'hover'" @mouseover="onEvent('over')" @mouseleave="onEvent('leave')">
      <div>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="10"
          height="10"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="feather feather-more-vertical"
        >
          <circle cx="12" cy="12" r="1" />
          <circle cx="12" cy="5" r="1" />
          <circle cx="12" cy="19" r="1" />
        </svg>
      </div>
      <div v-if="is_show_content" class="dropdown-content">
        <slot name="list_detail_button"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    type: String, // "click", "hover"
  },
  data() {
    return {
      is_show_content: false,
    };
  },
  methods: {
    onEvent(name) {
      if (this.type == "click") {
        // console.info(this.is_show_content);
        this.is_show_content = !this.is_show_content;
      } else if (this.type == "hover") {
        if (name == "over") {
          this.is_show_content = true;
        } else if (name == "leave") {
          this.is_show_content = false;
        }
      }
    },
    onClose(event) {
      this.is_show_content = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.dropdown-content {
  display: flex;
  flex-flow: column;
  background-color: #eaecf4;
  padding: 5px;
  z-index: 5;
  position: fixed;
}

.dropdown-content > a {
  padding: 10px;
  color: black;
}
.dropdown-content > a:hover {
  background-color: #b4b7ca;
}
</style>
