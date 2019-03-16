<template>
  <portal to="search" :disabled="alone">
    <div
      v-show="alone || !disabled"
      class="absolute opacity-75 w-full h-full bg-green-light animated fadeIn"
    >
      <div class="mx-auto max-w-xl h-full flex flex-col justify-center">
        <p v-html="message" class="text-green-lightest py-2"></p>
        <input
          placeholder="e.g. PRVU"
          ref="searchRef"
          @keydown.enter="enter"
          v-model="search"
          type="text"
          @keydown.esc="escape"
          class="border p-4 w-full"
          autofocus="true"
        >
      </div>
    </div>
  </portal>
</template>

<script>
import MouseTrap from "mousetrap";
export default {
  props: {
    alone: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: ""
    }
  },
  mounted() {
    document.onkeydown = key => {
      key.key === '/' && this.enable();
    };
    MouseTrap.bind("/", this.enable);
    MouseTrap.bind("esc", () => {
      this.disabled = true;
    });
    MouseTrap.bind("enter", () => {
      if (this.search.length < 3) {
        return;
      }
      this.enter();
    });
  },
  methods: {
    enable() {
      this.disabled = false;
      setTimeout(() => {
        this.$refs.searchRef.focus();
      }, 100);
    },
    escape() {
      this.disabled = true;
    },
    enter() {
      if (this.search.length < 3) {
        return;
      }
      window.location.href = "/report/" + this.search;
    }
  },
  data() {
    return {
      disabled: true,
      search: ""
    };
  },
  watch: {
    disabled(newVal) {
      if (newVal) {
        this.search = "";
      }
    }
  }
};
</script>

<style scoped>
input {
  text-transform: uppercase;
}
.animated {
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}
@-webkit-keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

.fadeIn {
  -webkit-animation-name: fadeIn;
  animation-name: fadeIn;
}

@-webkit-keyframes fadeInDown {
  from {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }

  to {
    opacity: 1;
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}
</style>


