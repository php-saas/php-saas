<script setup lang="ts">
import type { HTMLAttributes } from "vue";

defineOptions({
  inheritAttrs: false,
});

interface Props {
  className?: HTMLAttributes["class"];
}

defineProps<Props>();
</script>

<template>
  <svg
    :class="className"
    v-bind="$attrs"
    width="1024"
    height="1024"
    viewBox="0 0 1024 1024"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <rect width="1024" height="1024" rx="150" fill="url(#paint0_linear_29_4)" />
    <g filter="url(#filter0_d_29_4)">
      <path
        d="M287.188 646.687C242.125 684.54 227.104 796.896 227.104 796.896C227.104 796.896 339.46 781.875 377.313 736.813C398.642 711.578 398.342 672.824 374.609 649.391C362.932 638.246 347.55 631.806 331.416 631.307C315.281 630.808 299.531 636.285 287.188 646.687Z"
        stroke="white"
        stroke-width="30"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
      <path
        d="M512.5 601.625L422.375 511.5C438.362 470.025 458.491 430.269 482.458 392.835C517.462 336.867 566.203 290.785 624.045 258.972C681.886 227.16 746.905 210.674 812.917 211.083C812.917 292.797 789.484 436.396 632.667 541.542C594.721 565.537 554.464 585.665 512.5 601.625Z"
        fill="white"
        stroke="white"
        stroke-width="30"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
      <path
        d="M422.375 511.5L272.167 511.5C272.167 511.5 288.69 420.474 332.25 391.333C380.918 358.888 482.458 391.333 482.458 391.333"
        stroke="white"
        stroke-width="30"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
      <path
        d="M512.5 601.625L512.5 751.833C512.5 751.833 603.526 735.31 632.667 691.75C665.112 643.082 632.667 541.542 632.667 541.542"
        stroke="white"
        stroke-width="30"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
      <path
        d="M514.148 442.389L522.673 450.835C519.869 440.877 521.846 432.701 529.025 425.455C535.887 418.529 546.584 414.979 555.819 416.527C564.947 417.97 575.368 423.649 583.466 431.672C600.942 448.986 603.807 472.095 590.083 485.948C582.904 493.194 574.853 495.353 564.87 492.641L594.919 522.413L580.139 537.331L499.368 457.307L514.148 442.389ZM538.114 441.217C530.618 448.782 532.902 460.97 543.771 471.738C554.534 482.401 566.741 484.571 574.237 477.006C581.627 469.547 579.449 457.253 568.899 446.801C557.817 435.822 545.61 433.651 538.114 441.217ZM544.718 371.253L559.498 356.335L587.949 384.523C585.673 374.033 588.075 365.854 595.359 358.502C600.11 353.707 606.459 350.495 612.502 349.937C619.712 349.267 626.727 352.416 633.866 359.49L672.44 397.707L657.66 412.625L622.496 377.786C618.873 374.197 616.958 372.933 613.667 372.418C609.634 371.8 606.034 373.09 602.761 376.393C596.11 383.106 596.048 392.546 602.654 399.092L637.179 433.297L622.399 448.215L544.718 371.253ZM643.227 312.106L651.751 320.551C648.947 310.594 650.925 302.418 658.104 295.172C664.966 288.246 675.662 284.696 684.897 286.244C694.026 287.686 704.447 293.366 712.545 301.389C730.02 318.703 732.885 341.812 719.161 355.665C711.982 362.911 703.931 365.069 693.948 362.358L723.997 392.129L709.217 407.047L628.447 327.024L643.227 312.106ZM667.193 310.934C659.697 318.499 661.981 330.686 672.85 341.455C683.612 352.117 695.82 354.288 703.316 346.723C710.706 339.264 708.527 326.97 697.978 316.518C686.896 305.539 674.688 303.368 667.193 310.934Z"
        fill="#6575BF"
      />
    </g>
    <defs>
      <filter
        id="filter0_d_29_4"
        x="148"
        y="151"
        width="729"
        height="729"
        filterUnits="userSpaceOnUse"
        color-interpolation-filters="sRGB"
      >
        <feFlood flood-opacity="0" result="BackgroundImageFix" />
        <feColorMatrix
          in="SourceAlpha"
          type="matrix"
          values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
          result="hardAlpha"
        />
        <feOffset dy="4" />
        <feGaussianBlur stdDeviation="2" />
        <feComposite in2="hardAlpha" operator="out" />
        <feColorMatrix
          type="matrix"
          values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"
        />
        <feBlend
          mode="normal"
          in2="BackgroundImageFix"
          result="effect1_dropShadow_29_4"
        />
        <feBlend
          mode="normal"
          in="SourceGraphic"
          in2="effect1_dropShadow_29_4"
          result="shape"
        />
      </filter>
      <linearGradient
        id="paint0_linear_29_4"
        x1="512"
        y1="0"
        x2="512"
        y2="1024"
        gradientUnits="userSpaceOnUse"
      >
        <stop stop-color="#6575BF" />
        <stop offset="1" stop-color="#31395F" />
      </linearGradient>
    </defs>
  </svg>
</template>
