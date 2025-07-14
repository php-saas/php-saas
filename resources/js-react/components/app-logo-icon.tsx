import { Ref, SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement> & { ref?: Ref<SVGSVGElement> }) {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" width={1024} height={1024} viewBox="0 0 1024 1024" fill="none" {...props}>
      <rect width={1024} height={1024} fill="url(#a)" rx={150} />
      <g filter="url(#b)">
        <path
          stroke="#fff"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth={30}
          d="M287.188 646.687c-45.063 37.853-60.084 150.209-60.084 150.209s112.356-15.021 150.209-60.083c21.329-25.235 21.029-63.989-2.704-87.422a65.492 65.492 0 0 0-87.421-2.704Z"
        />
        <path
          fill="#fff"
          stroke="#fff"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth={30}
          d="M512.5 601.625 422.375 511.5a660.953 660.953 0 0 1 60.083-118.665 386.937 386.937 0 0 1 330.459-181.752c0 81.714-23.433 225.313-180.25 330.459A671.424 671.424 0 0 1 512.5 601.625Z"
        />
        <path
          stroke="#fff"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth={30}
          d="M422.375 511.5H272.167s16.523-91.026 60.083-120.167c48.668-32.445 150.208 0 150.208 0M512.5 601.625v150.208s91.026-16.523 120.167-60.083c32.445-48.668 0-150.208 0-150.208"
        />
        <path
          fill="#6575BF"
          d="m514.148 442.389 8.525 8.446c-2.804-9.958-.827-18.134 6.352-25.38 6.862-6.926 17.559-10.476 26.794-8.928 9.128 1.443 19.549 7.122 27.647 15.145 17.476 17.314 20.341 40.423 6.617 54.276-7.179 7.246-15.23 9.405-25.213 6.693l30.049 29.772-14.78 14.918-80.771-80.024 14.78-14.918Zm23.966-1.172c-7.496 7.565-5.212 19.753 5.657 30.521 10.763 10.663 22.97 12.833 30.466 5.268 7.39-7.459 5.212-19.753-5.338-30.205-11.082-10.979-23.289-13.15-30.785-5.584Zm6.604-69.964 14.78-14.918 28.451 28.188c-2.276-10.49.126-18.669 7.41-26.021 4.751-4.795 11.1-8.007 17.143-8.565 7.21-.67 14.225 2.479 21.364 9.553l38.574 38.217-14.78 14.918-35.164-34.839c-3.623-3.589-5.538-4.853-8.829-5.368-4.033-.618-7.633.672-10.906 3.975-6.651 6.713-6.713 16.153-.107 22.699l34.525 34.205-14.78 14.918-77.681-76.962Zm98.509-59.147 8.524 8.445c-2.804-9.957-.826-18.133 6.353-25.379 6.862-6.926 17.558-10.476 26.793-8.928 9.129 1.442 19.55 7.122 27.648 15.145 17.475 17.314 20.34 40.423 6.616 54.276-7.179 7.246-15.23 9.404-25.213 6.693l30.049 29.771-14.78 14.918-80.77-80.023 14.78-14.918Zm23.966-1.172c-7.496 7.565-5.212 19.752 5.657 30.521 10.762 10.662 22.97 12.833 30.466 5.268 7.39-7.459 5.211-19.753-5.338-30.205-11.082-10.979-23.29-13.15-30.785-5.584Z"
        />
      </g>
      <defs>
        <linearGradient id="a" x1={512} x2={512} y1={0} y2={1024} gradientUnits="userSpaceOnUse">
          <stop stopColor="#6575BF" />
          <stop offset={1} stopColor="#31395F" />
        </linearGradient>
        <filter id="b" width={729} height={729} x={148} y={151} colorInterpolationFilters="sRGB" filterUnits="userSpaceOnUse">
          <feFlood floodOpacity={0} result="BackgroundImageFix" />
          <feColorMatrix in="SourceAlpha" result="hardAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
          <feOffset dy={4} />
          <feGaussianBlur stdDeviation={2} />
          <feComposite in2="hardAlpha" operator="out" />
          <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
          <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_29_4" />
          <feBlend in="SourceGraphic" in2="effect1_dropShadow_29_4" result="shape" />
        </filter>
      </defs>
    </svg>
  );
}
