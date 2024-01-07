@extends('panel.layout.app')
@section('title', __('Publications'))

@section('content')

    <style>
        .chat-gpt-wrapper .flex-row {
            flex-direction: row;
        }

        .chat-gpt-wrapper .gap-3 {
            gap: .75rem;
        }

        .chat-gpt-wrapper [multiple],
        .chat-gpt-wrapper [type=date],
        .chat-gpt-wrapper [type=datetime-local],
        .chat-gpt-wrapper [type=email],
        .chat-gpt-wrapper [type=month],
        .chat-gpt-wrapper [type=number],
        .chat-gpt-wrapper [type=password],
        .chat-gpt-wrapper [type=search],
        .chat-gpt-wrapper [type=tel],
        .chat-gpt-wrapper [type=text],
        [type=time],
        .chat-gpt-wrapper [type=url],
        [type=week],
        .chat-gpt-wrapper select,
        .chat-gpt-wrapper textarea {
            --tw-shadow: 0 0 transparent;
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            border-color: #8e8ea0;
            border-radius: 0;
            border-width: 1px;
            font-size: 1rem;
            line-height: 1.5rem;
            padding: 1rem .75rem;
        }

        .hover\:bg-token-surface-primary:hover {
            background-color: #fff;
            background-color: #202123;
        }

        .chat-gpt-wrapper .dark .dark\:bg-black {
            --tw-bg-opacity: 1;
            background-color: rgba(0, 0, 0, 1) !important;

        }

        .chat-gpt-wrapper .left-side-bar a {
            color: #090A0A !important;
            text-decoration: none;
        }

        .chat-gpt-wrapper .left-side-bar ol li.relative:hover a {
            color: #fff !important;
        }

        .chat-gpt-wrapper .left-side-bar ol li.relative:hover a,
        .chat-gpt-wrapper .left-side-bar a:hover {
            color: #fff !important;
        }

        .text-gizmo-gray-600 {
            --tw-text-opacity: 1;
            color: rgba(102, 102, 102, 1);
        }

        .login-user-info button {
            background-color: transparent;
            color: #fff !important;
            border: none;
        }

        .login-user-info button .font-semibold {
            color: #fff !important;
        }

        .shadow-xxs {
            --tw-shadow: 0 1px 7px 0 rgba(0, 0, 0, .03);
            --tw-shadow-colored: 0 1px 7px 0 var(--tw-shadow-color);
        }

        .group:hover .group-hover\:opacity-100 {
            opacity: 1 !important;
        }

        .new-chat-btn {
            background-color: transparent;
            border: none;
            padding: 0 !important;
            color: #ececf1;
        }

        .left-side-bar ol {
            list-style: none !important;
            padding: 0;
            margin: 0;
        }

        .main-content-wrapper .text-gray-400.flex.self-end button {
            padding: 0 !important;
            border: none !important;
            background-color: #fff;
        }

        .theme-dark .chat-gpt-wrapper .left-side-bar a {
            color: #fff !important;
        }

        .theme-dark div#all_conversations h3 {
            background-color: transparent !important;
        }

        .theme-dark textarea#prompt-textarea {
            background-color: #1a1d23 !important;
        }

        .chat-gpt-wrapper :is(.theme-dark .dark\:text-black) {
            --tw-text-opacity: 1;
            color: rgb(0 0 0 / var(--tw-text-opacity)) !important;
            /* color: rgba(var(--tblr-white-rgb),var(--tblr-text-opacity))!important; */
        }

        @media (min-width: 1280px) {
            .xl\:max-w-3xl {
                max-width: 48rem;
            }
        }

        @media (min-width: 1024px) {
            .xl\:max-w-3xl {
                max-width: 48rem;
            }

            .lg\:mx-auto {
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (min-width: 768px) {
            .md\:mx-4 {
                margin-left: 1rem;
                margin-right: 1rem;
            }
        }
    </style>
    <div class="page-header">
        <div class="container-xl">
            <div class="row g-2 items-center">
                <div class="col">
                    <a href="{{ LaravelLocalization::localizeUrl(route('dashboard.index')) }}"
                        class="page-pretitle flex items-center">
                        <svg class="!me-2 rtl:-scale-x-100" width="8" height="10" viewBox="0 0 6 10" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.45536 9.45539C4.52679 9.45539 4.60714 9.41968 4.66071 9.36611L5.10714 8.91968C5.16071 8.86611 5.19643 8.78575 5.19643 8.71432C5.19643 8.64289 5.16071 8.56254 5.10714 8.50896L1.59821 5.00004L5.10714 1.49111C5.16071 1.43753 5.19643 1.35718 5.19643 1.28575C5.19643 1.20539 5.16071 1.13396 5.10714 1.08039L4.66071 0.633963C4.60714 0.580392 4.52679 0.544678 4.45536 0.544678C4.38393 0.544678 4.30357 0.580392 4.25 0.633963L0.0892856 4.79468C0.0357141 4.84825 0 4.92861 0 5.00004C0 5.07146 0.0357141 5.15182 0.0892856 5.20539L4.25 9.36611C4.30357 9.41968 4.38393 9.45539 4.45536 9.45539Z" />
                        </svg>
                        {{ __('Back to dashboard') }}
                    </a>
                    <h2 class="page-title mb-2">
                        {{ __('Zeus AI') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body pt-6">
        <div class="container-xl">
            {{-- <div class="chat-gpt-wrapper relative z-0 flex h-full w-full overflow-hidden"> --}}
            <div
                class="chat-gpt-wrapper relative z-0 flex w-full overflow-hidden h-[75vh] max-md:flex-col-reverse max-md:h-auto">
                <div class="left-side-bar flex-shrink-0 overflow-x-hidden" style="width: 260px;">
                    <div class="h-full w-[260px]">
                        <div class="flex h-full min-h-0 flex-col">
                            <div class="flex h-full min-h-0 flex-col transition-opacity opacity-100">
                                <div class="scrollbar-trigger relative h-full w-full flex-1 items-start border-white/20">
                                    <h2
                                        style="position: absolute; border: 0px; width: 1px; height: 1px; padding: 0px; margin: -1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; overflow-wrap: normal;">
                                        Chat history</h2>
                                    <nav class="flex h-full w-full flex-col px-3 pb-3.5 border-l-0 border-b-0 border-t-0 border-solid border-[--tblr-border-color]"
                                        aria-label="Chat history">
                                        <div
                                            class="absolute left-0 top-0 z-20 overflow-hidden transition-all duration-500 invisible max-h-0">
                                            <div class="px-3 py-3.5">
                                                <div class="pb-0.5 last:pb-0" tabindex="0" data-projection-id="1">
                                                    <a class="group flex h-10 items-center gap-2 rounded-lg px-2 font-medium hover:bg-token-surface-primary"
                                                        href="/">
                                                        <div class="h-7 w-7 flex-shrink-0">
                                                            <div
                                                                class="gizmo-shadow-stroke relative flex h-full items-center justify-center rounded-full bg-white text-black">
                                                                <svg width="41" height="41" viewBox="0 0 41 41"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-2/3 w-2/3" role="img">
                                                                    <text x="-9999" y="-9999">ChatGPT</text>
                                                                    <path
                                                                        d="M37.5324 16.8707C37.9808 15.5241 38.1363 14.0974 37.9886 12.6859C37.8409 11.2744 37.3934 9.91076 36.676 8.68622C35.6126 6.83404 33.9882 5.3676 32.0373 4.4985C30.0864 3.62941 27.9098 3.40259 25.8215 3.85078C24.8796 2.7893 23.7219 1.94125 22.4257 1.36341C21.1295 0.785575 19.7249 0.491269 18.3058 0.500197C16.1708 0.495044 14.0893 1.16803 12.3614 2.42214C10.6335 3.67624 9.34853 5.44666 8.6917 7.47815C7.30085 7.76286 5.98686 8.3414 4.8377 9.17505C3.68854 10.0087 2.73073 11.0782 2.02839 12.312C0.956464 14.1591 0.498905 16.2988 0.721698 18.4228C0.944492 20.5467 1.83612 22.5449 3.268 24.1293C2.81966 25.4759 2.66413 26.9026 2.81182 28.3141C2.95951 29.7256 3.40701 31.0892 4.12437 32.3138C5.18791 34.1659 6.8123 35.6322 8.76321 36.5013C10.7141 37.3704 12.8907 37.5973 14.9789 37.1492C15.9208 38.2107 17.0786 39.0587 18.3747 39.6366C19.6709 40.2144 21.0755 40.5087 22.4946 40.4998C24.6307 40.5054 26.7133 39.8321 28.4418 38.5772C30.1704 37.3223 31.4556 35.5506 32.1119 33.5179C33.5027 33.2332 34.8167 32.6547 35.9659 31.821C37.115 30.9874 38.0728 29.9178 38.7752 28.684C39.8458 26.8371 40.3023 24.6979 40.0789 22.5748C39.8556 20.4517 38.9639 18.4544 37.5324 16.8707ZM22.4978 37.8849C20.7443 37.8874 19.0459 37.2733 17.6994 36.1501C17.7601 36.117 17.8666 36.0586 17.936 36.0161L25.9004 31.4156C26.1003 31.3019 26.2663 31.137 26.3813 30.9378C26.4964 30.7386 26.5563 30.5124 26.5549 30.2825V19.0542L29.9213 20.998C29.9389 21.0068 29.9541 21.0198 29.9656 21.0359C29.977 21.052 29.9842 21.0707 29.9867 21.0902V30.3889C29.9842 32.375 29.1946 34.2791 27.7909 35.6841C26.3872 37.0892 24.4838 37.8806 22.4978 37.8849ZM6.39227 31.0064C5.51397 29.4888 5.19742 27.7107 5.49804 25.9832C5.55718 26.0187 5.66048 26.0818 5.73461 26.1244L13.699 30.7248C13.8975 30.8408 14.1233 30.902 14.3532 30.902C14.583 30.902 14.8088 30.8408 15.0073 30.7248L24.731 25.1103V28.9979C24.7321 29.0177 24.7283 29.0376 24.7199 29.0556C24.7115 29.0736 24.6988 29.0893 24.6829 29.1012L16.6317 33.7497C14.9096 34.7416 12.8643 35.0097 10.9447 34.4954C9.02506 33.9811 7.38785 32.7263 6.39227 31.0064ZM4.29707 13.6194C5.17156 12.0998 6.55279 10.9364 8.19885 10.3327C8.19885 10.4013 8.19491 10.5228 8.19491 10.6071V19.808C8.19351 20.0378 8.25334 20.2638 8.36823 20.4629C8.48312 20.6619 8.64893 20.8267 8.84863 20.9404L18.5723 26.5542L15.206 28.4979C15.1894 28.5089 15.1703 28.5155 15.1505 28.5173C15.1307 28.5191 15.1107 28.516 15.0924 28.5082L7.04046 23.8557C5.32135 22.8601 4.06716 21.2235 3.55289 19.3046C3.03862 17.3858 3.30624 15.3413 4.29707 13.6194ZM31.955 20.0556L22.2312 14.4411L25.5976 12.4981C25.6142 12.4872 25.6333 12.4805 25.6531 12.4787C25.6729 12.4769 25.6928 12.4801 25.7111 12.4879L33.7631 17.1364C34.9967 17.849 36.0017 18.8982 36.6606 20.1613C37.3194 21.4244 37.6047 22.849 37.4832 24.2684C37.3617 25.6878 36.8382 27.0432 35.9743 28.1759C35.1103 29.3086 33.9415 30.1717 32.6047 30.6641C32.6047 30.5947 32.6047 30.4733 32.6047 30.3889V21.188C32.6066 20.9586 32.5474 20.7328 32.4332 20.5338C32.319 20.3348 32.154 20.1698 31.955 20.0556ZM35.3055 15.0128C35.2464 14.9765 35.1431 14.9142 35.069 14.8717L27.1045 10.2712C26.906 10.1554 26.6803 10.0943 26.4504 10.0943C26.2206 10.0943 25.9948 10.1554 25.7963 10.2712L16.0726 15.8858V11.9982C16.0715 11.9783 16.0753 11.9585 16.0837 11.9405C16.0921 11.9225 16.1048 11.9068 16.1207 11.8949L24.1719 7.25025C25.4053 6.53903 26.8158 6.19376 28.2383 6.25482C29.6608 6.31589 31.0364 6.78077 32.2044 7.59508C33.3723 8.40939 34.2842 9.53945 34.8334 10.8531C35.3826 12.1667 35.5464 13.6095 35.3055 15.0128ZM14.2424 21.9419L10.8752 19.9981C10.8576 19.9893 10.8423 19.9763 10.8309 19.9602C10.8195 19.9441 10.8122 19.9254 10.8098 19.9058V10.6071C10.8107 9.18295 11.2173 7.78848 11.9819 6.58696C12.7466 5.38544 13.8377 4.42659 15.1275 3.82264C16.4173 3.21869 17.8524 2.99464 19.2649 3.1767C20.6775 3.35876 22.0089 3.93941 23.1034 4.85067C23.0427 4.88379 22.937 4.94215 22.8668 4.98473L14.9024 9.58517C14.7025 9.69878 14.5366 9.86356 14.4215 10.0626C14.3065 10.2616 14.2466 10.4877 14.2479 10.7175L14.2424 21.9419ZM16.071 17.9991L20.4018 15.4978L24.7325 17.9975V22.9985L20.4018 25.4983L16.071 22.9985V17.9991Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="grow overflow-hidden text-ellipsis whitespace-nowrap text-sm text-token-text-primary">
                                                            Clear chat
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <span class="flex items-center" data-state="closed">
                                                                <button class="text-token-text-primary">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon-md">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M4.5 2.5C5.05228 2.5 5.5 2.94772 5.5 3.5V5.07196C7.19872 3.47759 9.48483 2.5 12 2.5C17.2467 2.5 21.5 6.75329 21.5 12C21.5 17.2467 17.2467 21.5 12 21.5C7.1307 21.5 3.11828 17.8375 2.565 13.1164C2.50071 12.5679 2.89327 12.0711 3.4418 12.0068C3.99033 11.9425 4.48712 12.3351 4.5514 12.8836C4.98798 16.6089 8.15708 19.5 12 19.5C16.1421 19.5 19.5 16.1421 19.5 12C19.5 7.85786 16.1421 4.5 12 4.5C9.7796 4.5 7.7836 5.46469 6.40954 7H9C9.55228 7 10 7.44772 10 8C10 8.55228 9.55228 9 9 9H4.5C3.96064 9 3.52101 8.57299 3.50073 8.03859C3.49983 8.01771 3.49958 7.99677 3.5 7.9758V3.5C3.5 2.94772 3.94771 2.5 4.5 2.5Z"
                                                                            fill="currentColor"></path>
                                                                    </svg>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="h-24 bg-gradient-to-t from-gray-900/0 to-gray-900"></div>
                                        </div>
                                        <div class="flex-col flex-1 transition-opacity duration-500 -mr-2 pr-2 ">
                                            <div class="sticky left-0 right-0 top-0 z-20 pt-3.5">
                                                <div class="pb-0.5 last:pb-0" tabindex="0" style="transform: none;">
                                                    <a class="group flex h-10 items-center gap-2 rounded-lg px-2 font-medium hover:bg-token-surface-primary"
                                                        href="{{ route('dashboard.user.zeusai.chat') }}">
                                                        <div class="h-7 w-7 flex-shrink-0">
                                                            <div
                                                                class="gizmo-shadow-stroke relative flex h-full items-center justify-center rounded-full bg-white text-black">
                                                                <svg width="41" height="41" viewBox="0 0 41 41"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-2/3 w-2/3" role="img">
                                                                    <text x="-9999" y="-9999">ChatGPT</text>
                                                                    <path
                                                                        d="M37.5324 16.8707C37.9808 15.5241 38.1363 14.0974 37.9886 12.6859C37.8409 11.2744 37.3934 9.91076 36.676 8.68622C35.6126 6.83404 33.9882 5.3676 32.0373 4.4985C30.0864 3.62941 27.9098 3.40259 25.8215 3.85078C24.8796 2.7893 23.7219 1.94125 22.4257 1.36341C21.1295 0.785575 19.7249 0.491269 18.3058 0.500197C16.1708 0.495044 14.0893 1.16803 12.3614 2.42214C10.6335 3.67624 9.34853 5.44666 8.6917 7.47815C7.30085 7.76286 5.98686 8.3414 4.8377 9.17505C3.68854 10.0087 2.73073 11.0782 2.02839 12.312C0.956464 14.1591 0.498905 16.2988 0.721698 18.4228C0.944492 20.5467 1.83612 22.5449 3.268 24.1293C2.81966 25.4759 2.66413 26.9026 2.81182 28.3141C2.95951 29.7256 3.40701 31.0892 4.12437 32.3138C5.18791 34.1659 6.8123 35.6322 8.76321 36.5013C10.7141 37.3704 12.8907 37.5973 14.9789 37.1492C15.9208 38.2107 17.0786 39.0587 18.3747 39.6366C19.6709 40.2144 21.0755 40.5087 22.4946 40.4998C24.6307 40.5054 26.7133 39.8321 28.4418 38.5772C30.1704 37.3223 31.4556 35.5506 32.1119 33.5179C33.5027 33.2332 34.8167 32.6547 35.9659 31.821C37.115 30.9874 38.0728 29.9178 38.7752 28.684C39.8458 26.8371 40.3023 24.6979 40.0789 22.5748C39.8556 20.4517 38.9639 18.4544 37.5324 16.8707ZM22.4978 37.8849C20.7443 37.8874 19.0459 37.2733 17.6994 36.1501C17.7601 36.117 17.8666 36.0586 17.936 36.0161L25.9004 31.4156C26.1003 31.3019 26.2663 31.137 26.3813 30.9378C26.4964 30.7386 26.5563 30.5124 26.5549 30.2825V19.0542L29.9213 20.998C29.9389 21.0068 29.9541 21.0198 29.9656 21.0359C29.977 21.052 29.9842 21.0707 29.9867 21.0902V30.3889C29.9842 32.375 29.1946 34.2791 27.7909 35.6841C26.3872 37.0892 24.4838 37.8806 22.4978 37.8849ZM6.39227 31.0064C5.51397 29.4888 5.19742 27.7107 5.49804 25.9832C5.55718 26.0187 5.66048 26.0818 5.73461 26.1244L13.699 30.7248C13.8975 30.8408 14.1233 30.902 14.3532 30.902C14.583 30.902 14.8088 30.8408 15.0073 30.7248L24.731 25.1103V28.9979C24.7321 29.0177 24.7283 29.0376 24.7199 29.0556C24.7115 29.0736 24.6988 29.0893 24.6829 29.1012L16.6317 33.7497C14.9096 34.7416 12.8643 35.0097 10.9447 34.4954C9.02506 33.9811 7.38785 32.7263 6.39227 31.0064ZM4.29707 13.6194C5.17156 12.0998 6.55279 10.9364 8.19885 10.3327C8.19885 10.4013 8.19491 10.5228 8.19491 10.6071V19.808C8.19351 20.0378 8.25334 20.2638 8.36823 20.4629C8.48312 20.6619 8.64893 20.8267 8.84863 20.9404L18.5723 26.5542L15.206 28.4979C15.1894 28.5089 15.1703 28.5155 15.1505 28.5173C15.1307 28.5191 15.1107 28.516 15.0924 28.5082L7.04046 23.8557C5.32135 22.8601 4.06716 21.2235 3.55289 19.3046C3.03862 17.3858 3.30624 15.3413 4.29707 13.6194ZM31.955 20.0556L22.2312 14.4411L25.5976 12.4981C25.6142 12.4872 25.6333 12.4805 25.6531 12.4787C25.6729 12.4769 25.6928 12.4801 25.7111 12.4879L33.7631 17.1364C34.9967 17.849 36.0017 18.8982 36.6606 20.1613C37.3194 21.4244 37.6047 22.849 37.4832 24.2684C37.3617 25.6878 36.8382 27.0432 35.9743 28.1759C35.1103 29.3086 33.9415 30.1717 32.6047 30.6641C32.6047 30.5947 32.6047 30.4733 32.6047 30.3889V21.188C32.6066 20.9586 32.5474 20.7328 32.4332 20.5338C32.319 20.3348 32.154 20.1698 31.955 20.0556ZM35.3055 15.0128C35.2464 14.9765 35.1431 14.9142 35.069 14.8717L27.1045 10.2712C26.906 10.1554 26.6803 10.0943 26.4504 10.0943C26.2206 10.0943 25.9948 10.1554 25.7963 10.2712L16.0726 15.8858V11.9982C16.0715 11.9783 16.0753 11.9585 16.0837 11.9405C16.0921 11.9225 16.1048 11.9068 16.1207 11.8949L24.1719 7.25025C25.4053 6.53903 26.8158 6.19376 28.2383 6.25482C29.6608 6.31589 31.0364 6.78077 32.2044 7.59508C33.3723 8.40939 34.2842 9.53945 34.8334 10.8531C35.3826 12.1667 35.5464 13.6095 35.3055 15.0128ZM14.2424 21.9419L10.8752 19.9981C10.8576 19.9893 10.8423 19.9763 10.8309 19.9602C10.8195 19.9441 10.8122 19.9254 10.8098 19.9058V10.6071C10.8107 9.18295 11.2173 7.78848 11.9819 6.58696C12.7466 5.38544 13.8377 4.42659 15.1275 3.82264C16.4173 3.21869 17.8524 2.99464 19.2649 3.1767C20.6775 3.35876 22.0089 3.93941 23.1034 4.85067C23.0427 4.88379 22.937 4.94215 22.8668 4.98473L14.9024 9.58517C14.7025 9.69878 14.5366 9.86356 14.4215 10.0626C14.3065 10.2616 14.2466 10.4877 14.2479 10.7175L14.2424 21.9419ZM16.071 17.9991L20.4018 15.4978L24.7325 17.9975V22.9985L20.4018 25.4983L16.071 22.9985V17.9991Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="grow overflow-hidden text-ellipsis whitespace-nowrap text-sm text-token-text-primary">
                                                            New chat
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <span class="flex items-center" data-state="closed">
                                                                <button class="text-token-text-primary new-chat-btn">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon-md">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M16.7929 2.79289C18.0118 1.57394 19.9882 1.57394 21.2071 2.79289C22.4261 4.01184 22.4261 5.98815 21.2071 7.20711L12.7071 15.7071C12.5196 15.8946 12.2652 16 12 16H9C8.44772 16 8 15.5523 8 15V12C8 11.7348 8.10536 11.4804 8.29289 11.2929L16.7929 2.79289ZM19.7929 4.20711C19.355 3.7692 18.645 3.7692 18.2071 4.2071L10 12.4142V14H11.5858L19.7929 5.79289C20.2308 5.35499 20.2308 4.64501 19.7929 4.20711ZM6 5C5.44772 5 5 5.44771 5 6V18C5 18.5523 5.44772 19 6 19H18C18.5523 19 19 18.5523 19 18V14C19 13.4477 19.4477 13 20 13C20.5523 13 21 13.4477 21 14V18C21 19.6569 19.6569 21 18 21H6C4.34315 21 3 19.6569 3 18V6C3 4.34314 4.34315 3 6 3H10C10.5523 3 11 3.44771 11 4C11 4.55228 10.5523 5 10 5H6Z"
                                                                            fill="currentColor"></path>
                                                                    </svg>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-2 pb-2 dark:text-gray-100 text-gray-800 text-sm">
                                                <div id="all_conversations" class="overflow-y-auto h-[60vh]">

                                                </div>
                                            </div>
                                        </div>

                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-content-wrapper relative flex h-full max-w-full flex-1 flex-col overflow-hidden">
                    <main class="relative h-full w-full flex-1 overflow-auto transition-width">
                        <div class="fixed left-0 top-1/2 z-40"
                            style="display: none !important; transform: translateX(260px) translateY(-50%) rotate(0deg) translateZ(0px);">
                            <button>
                                <span class="" data-state="closed">
                                    <div class="flex h-[72px] w-8 items-center justify-center" style="opacity: 0.25;">
                                        <div class="flex h-6 w-6 flex-col items-center">
                                            <div class="h-3 w-1 rounded-full bg-token-text-primary"
                                                style="transform: translateY(0.15rem) rotate(0deg) translateZ(0px);"></div>
                                            <div class="h-3 w-1 rounded-full bg-token-text-primary"
                                                style="transform: translateY(-0.15rem) rotate(0deg) translateZ(0px);">
                                            </div>
                                        </div>
                                    </div>
                                    <span
                                        style="position: absolute; border: 0px; width: 1px; height: 1px; padding: 0px; margin: -1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; overflow-wrap: normal;">Close
                                        sidebar</span>
                                </span>
                            </button>
                        </div>
                        <div role="presentation" class="flex h-full flex-col">
                            <div class="flex-1 overflow-auto" id="scrollable_content">
                                <div class="h-full">
                                    <div class="chats-container h-full overflow-auto p-8 max-md:max-h-[60vh] max-md:p-4">
                                        @if (!isset($conversation->id))
                                            <div id="home_suggestions">
                                                <div class="relative h-full">
                                                    <div class="left-0 right-0">
                                                        <div
                                                            class="sticky top-0 mb-1.5 flex items-center justify-between z-10 h-14 bg-white p-2 font-semibold dark:bg-gray-800">
                                                            <div class="absolute left-1/2 -translate-x-1/2"></div>
                                                            <div class="flex items-center gap-2">
                                                                <div class="group flex cursor-pointer items-center gap-1 rounded-xl py-2 px-3 text-lg font-medium hover:bg-gray-50 radix-state-open:bg-gray-50 dark:hover:bg-black/10 dark:radix-state-open:bg-black/20"
                                                                    type="button" id="radix-:r1v:" aria-haspopup="menu"
                                                                    aria-expanded="false" data-state="closed">
                                                                    <div>ChatGPT <span
                                                                            class="text-token-text-secondary">3.5</span>
                                                                    </div>
                                                                    <svg width="16" height="17"
                                                                        viewBox="0 0 16 17" fill="none"
                                                                        class="text-token-text-tertiary">
                                                                        <path
                                                                            d="M11.3346 7.83203L8.00131 11.1654L4.66797 7.83203"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <div class="flex gap-2 pr-1"></div>
                                                        </div>
                                                    </div>
                                                    <div class="flex h-full flex-col items-center justify-center">
                                                        <div class="relative">
                                                            <div class="mb-3 h-[72px] w-[72px]">
                                                                <div
                                                                    class="gizmo-shadow-stroke relative flex h-full items-center justify-center rounded-full bg-white text-black">
                                                                    <svg width="41" height="41"
                                                                        viewBox="0 0 41 41" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-2/3 w-2/3" role="img"><text x="-9999"
                                                                            y="-9999">ChatGPT</text>
                                                                        <path
                                                                            d="M37.5324 16.8707C37.9808 15.5241 38.1363 14.0974 37.9886 12.6859C37.8409 11.2744 37.3934 9.91076 36.676 8.68622C35.6126 6.83404 33.9882 5.3676 32.0373 4.4985C30.0864 3.62941 27.9098 3.40259 25.8215 3.85078C24.8796 2.7893 23.7219 1.94125 22.4257 1.36341C21.1295 0.785575 19.7249 0.491269 18.3058 0.500197C16.1708 0.495044 14.0893 1.16803 12.3614 2.42214C10.6335 3.67624 9.34853 5.44666 8.6917 7.47815C7.30085 7.76286 5.98686 8.3414 4.8377 9.17505C3.68854 10.0087 2.73073 11.0782 2.02839 12.312C0.956464 14.1591 0.498905 16.2988 0.721698 18.4228C0.944492 20.5467 1.83612 22.5449 3.268 24.1293C2.81966 25.4759 2.66413 26.9026 2.81182 28.3141C2.95951 29.7256 3.40701 31.0892 4.12437 32.3138C5.18791 34.1659 6.8123 35.6322 8.76321 36.5013C10.7141 37.3704 12.8907 37.5973 14.9789 37.1492C15.9208 38.2107 17.0786 39.0587 18.3747 39.6366C19.6709 40.2144 21.0755 40.5087 22.4946 40.4998C24.6307 40.5054 26.7133 39.8321 28.4418 38.5772C30.1704 37.3223 31.4556 35.5506 32.1119 33.5179C33.5027 33.2332 34.8167 32.6547 35.9659 31.821C37.115 30.9874 38.0728 29.9178 38.7752 28.684C39.8458 26.8371 40.3023 24.6979 40.0789 22.5748C39.8556 20.4517 38.9639 18.4544 37.5324 16.8707ZM22.4978 37.8849C20.7443 37.8874 19.0459 37.2733 17.6994 36.1501C17.7601 36.117 17.8666 36.0586 17.936 36.0161L25.9004 31.4156C26.1003 31.3019 26.2663 31.137 26.3813 30.9378C26.4964 30.7386 26.5563 30.5124 26.5549 30.2825V19.0542L29.9213 20.998C29.9389 21.0068 29.9541 21.0198 29.9656 21.0359C29.977 21.052 29.9842 21.0707 29.9867 21.0902V30.3889C29.9842 32.375 29.1946 34.2791 27.7909 35.6841C26.3872 37.0892 24.4838 37.8806 22.4978 37.8849ZM6.39227 31.0064C5.51397 29.4888 5.19742 27.7107 5.49804 25.9832C5.55718 26.0187 5.66048 26.0818 5.73461 26.1244L13.699 30.7248C13.8975 30.8408 14.1233 30.902 14.3532 30.902C14.583 30.902 14.8088 30.8408 15.0073 30.7248L24.731 25.1103V28.9979C24.7321 29.0177 24.7283 29.0376 24.7199 29.0556C24.7115 29.0736 24.6988 29.0893 24.6829 29.1012L16.6317 33.7497C14.9096 34.7416 12.8643 35.0097 10.9447 34.4954C9.02506 33.9811 7.38785 32.7263 6.39227 31.0064ZM4.29707 13.6194C5.17156 12.0998 6.55279 10.9364 8.19885 10.3327C8.19885 10.4013 8.19491 10.5228 8.19491 10.6071V19.808C8.19351 20.0378 8.25334 20.2638 8.36823 20.4629C8.48312 20.6619 8.64893 20.8267 8.84863 20.9404L18.5723 26.5542L15.206 28.4979C15.1894 28.5089 15.1703 28.5155 15.1505 28.5173C15.1307 28.5191 15.1107 28.516 15.0924 28.5082L7.04046 23.8557C5.32135 22.8601 4.06716 21.2235 3.55289 19.3046C3.03862 17.3858 3.30624 15.3413 4.29707 13.6194ZM31.955 20.0556L22.2312 14.4411L25.5976 12.4981C25.6142 12.4872 25.6333 12.4805 25.6531 12.4787C25.6729 12.4769 25.6928 12.4801 25.7111 12.4879L33.7631 17.1364C34.9967 17.849 36.0017 18.8982 36.6606 20.1613C37.3194 21.4244 37.6047 22.849 37.4832 24.2684C37.3617 25.6878 36.8382 27.0432 35.9743 28.1759C35.1103 29.3086 33.9415 30.1717 32.6047 30.6641C32.6047 30.5947 32.6047 30.4733 32.6047 30.3889V21.188C32.6066 20.9586 32.5474 20.7328 32.4332 20.5338C32.319 20.3348 32.154 20.1698 31.955 20.0556ZM35.3055 15.0128C35.2464 14.9765 35.1431 14.9142 35.069 14.8717L27.1045 10.2712C26.906 10.1554 26.6803 10.0943 26.4504 10.0943C26.2206 10.0943 25.9948 10.1554 25.7963 10.2712L16.0726 15.8858V11.9982C16.0715 11.9783 16.0753 11.9585 16.0837 11.9405C16.0921 11.9225 16.1048 11.9068 16.1207 11.8949L24.1719 7.25025C25.4053 6.53903 26.8158 6.19376 28.2383 6.25482C29.6608 6.31589 31.0364 6.78077 32.2044 7.59508C33.3723 8.40939 34.2842 9.53945 34.8334 10.8531C35.3826 12.1667 35.5464 13.6095 35.3055 15.0128ZM14.2424 21.9419L10.8752 19.9981C10.8576 19.9893 10.8423 19.9763 10.8309 19.9602C10.8195 19.9441 10.8122 19.9254 10.8098 19.9058V10.6071C10.8107 9.18295 11.2173 7.78848 11.9819 6.58696C12.7466 5.38544 13.8377 4.42659 15.1275 3.82264C16.4173 3.21869 17.8524 2.99464 19.2649 3.1767C20.6775 3.35876 22.0089 3.93941 23.1034 4.85067C23.0427 4.88379 22.937 4.94215 22.8668 4.98473L14.9024 9.58517C14.7025 9.69878 14.5366 9.86356 14.4215 10.0626C14.3065 10.2616 14.2466 10.4877 14.2479 10.7175L14.2424 21.9419ZM16.071 17.9991L20.4018 15.4978L24.7325 17.9975V22.9985L20.4018 25.4983L16.071 22.9985V17.9991Z"
                                                                            fill="currentColor"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-5 text-2xl font-medium">How can I help you today?
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="absolute bottom-full left-0 mb-4 flex w-full grow gap-2 px-1 pb-1 sm:px-2 sm:pb-0 md:static md:mb-0 md:max-w-none">
                                                    <div
                                                        class="grid w-full grid-flow-row grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-3">
                                                        <div class="flex flex-col gap-3">
                                                            <span data-projection-id="38"
                                                                style="opacity: 1; transform: none;"><button
                                                                    class="suggestion-button btn relative btn-neutral group w-full whitespace-nowrap rounded-xl px-4 py-3 text-left text-gray-700 dark:text-gray-300 md:whitespace-normal"
                                                                    as="button">
                                                                    <div
                                                                        class="flex w-full gap-2 items-center justify-center">
                                                                        <div
                                                                            class="flex w-full items-center justify-between">
                                                                            <div class="flex flex-col overflow-hidden">
                                                                                <div class="truncate">Plan a trip
                                                                                </div>
                                                                                <div
                                                                                    class="truncate font-normal opacity-50">
                                                                                    to
                                                                                    experience Seoul like a local
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="absolute bottom-0 right-0 top-0 flex items-center rounded-xl bg-gradient-to-l from-gray-50 from-[60%] pl-6 pr-4 text-gray-700 opacity-0 group-hover:opacity-100 dark:from-gray-700 dark:text-gray-200">
                                                                                <span class="" data-state="closed">
                                                                                    <div
                                                                                        class="rounded-lg bg-token-surface-primary p-1 shadow-xxs dark:shadow-none">
                                                                                        <svg width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            class="icon-sm text-token-text-primary">
                                                                                            <path
                                                                                                d="M7 11L12 6L17 11M12 18V7"
                                                                                                stroke="currentColor"
                                                                                                stroke-width="2"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </span>
                                                            <span data-projection-id="39"
                                                                style="opacity: 1; transform: none;"><button
                                                                    class="suggestion-button btn relative btn-neutral group w-full whitespace-nowrap rounded-xl px-4 py-3 text-left text-gray-700 dark:text-gray-300 md:whitespace-normal"
                                                                    as="button">
                                                                    <div
                                                                        class="flex w-full gap-2 items-center justify-center">
                                                                        <div
                                                                            class="flex w-full items-center justify-between">
                                                                            <div class="flex flex-col overflow-hidden">
                                                                                <div class="truncate">Show me a
                                                                                    code snippet
                                                                                </div>
                                                                                <div
                                                                                    class="truncate font-normal opacity-50">
                                                                                    of
                                                                                    a website's sticky header</div>
                                                                            </div>
                                                                            <div
                                                                                class="absolute bottom-0 right-0 top-0 flex items-center rounded-xl bg-gradient-to-l from-gray-50 from-[60%] pl-6 pr-4 text-gray-700 opacity-0 group-hover:opacity-100 dark:from-gray-700 dark:text-gray-200">
                                                                                <span class="" data-state="closed">
                                                                                    <div
                                                                                        class="rounded-lg bg-token-surface-primary p-1 shadow-xxs dark:shadow-none">
                                                                                        <svg width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            class="icon-sm text-token-text-primary">
                                                                                            <path
                                                                                                d="M7 11L12 6L17 11M12 18V7"
                                                                                                stroke="currentColor"
                                                                                                stroke-width="2"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </button></span>
                                                        </div>
                                                        <div class="flex flex-col gap-3"><span data-projection-id="40"
                                                                style="opacity: 1; transform: none;"><button
                                                                    class="suggestion-button btn relative btn-neutral group w-full whitespace-nowrap rounded-xl px-4 py-3 text-left text-gray-700 dark:text-gray-300 md:whitespace-normal"
                                                                    as="button">
                                                                    <div
                                                                        class="flex w-full gap-2 items-center justify-center">
                                                                        <div
                                                                            class="flex w-full items-center justify-between">
                                                                            <div class="flex flex-col overflow-hidden">
                                                                                <div class="truncate">Write a
                                                                                    Python script
                                                                                </div>
                                                                                <div
                                                                                    class="truncate font-normal opacity-50">
                                                                                    to
                                                                                    automate sending daily email
                                                                                    reports</div>
                                                                            </div>
                                                                            <div
                                                                                class="absolute bottom-0 right-0 top-0 flex items-center rounded-xl bg-gradient-to-l from-gray-50 from-[60%] pl-6 pr-4 text-gray-700 opacity-0 group-hover:opacity-100 dark:from-gray-700 dark:text-gray-200">
                                                                                <span class="" data-state="closed">
                                                                                    <div
                                                                                        class="rounded-lg bg-token-surface-primary p-1 shadow-xxs dark:shadow-none">
                                                                                        <svg width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            class="icon-sm text-token-text-primary">
                                                                                            <path
                                                                                                d="M7 11L12 6L17 11M12 18V7"
                                                                                                stroke="currentColor"
                                                                                                stroke-width="2"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </button></span><span data-projection-id="41"
                                                                style="opacity: 1; transform: none;"><button
                                                                    class="suggestion-button btn relative btn-neutral group w-full whitespace-nowrap rounded-xl px-4 py-3 text-left text-gray-700 dark:text-gray-300 md:whitespace-normal"
                                                                    as="button">
                                                                    <div
                                                                        class="flex w-full gap-2 items-center justify-center">
                                                                        <div
                                                                            class="flex w-full items-center justify-between">
                                                                            <div class="flex flex-col overflow-hidden">
                                                                                <div class="truncate">Recommend a
                                                                                    dish</div>
                                                                                <div
                                                                                    class="truncate font-normal opacity-50">
                                                                                    to
                                                                                    bring to a potluck</div>
                                                                            </div>
                                                                            <div
                                                                                class="absolute bottom-0 right-0 top-0 flex items-center rounded-xl bg-gradient-to-l from-gray-50 from-[60%] pl-6 pr-4 text-gray-700 opacity-0 group-hover:opacity-100 dark:from-gray-700 dark:text-gray-200">
                                                                                <span class="" data-state="closed">
                                                                                    <div
                                                                                        class="rounded-lg bg-token-surface-primary p-1 shadow-xxs dark:shadow-none">
                                                                                        <svg width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            class="icon-sm text-token-text-primary">
                                                                                            <path
                                                                                                d="M7 11L12 6L17 11M12 18V7"
                                                                                                stroke="currentColor"
                                                                                                stroke-width="2"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </button></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @include('panel.zeus_ai.components.chat_area')
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="w-full px-4 pt-2 md:pt-0 dark:border-white/20 md:border-transparent md:dark:border-transparent md:w-[calc(100%-.5rem)]">
                                @if ($setting->hosting_type != 'high')
                                    <input type="hidden" id="guest_id" value="{{ $apiUrl }}">
                                    <input type="hidden" id="guest_event_id" value="{{ $apikeyPart1 }}">
                                    <input type="hidden" id="guest_look_id" value="{{ $apikeyPart2 }}">
                                    <input type="hidden" id="guest_product_id" value="{{ $apikeyPart3 }}">
                                @endif

                                <div class="relative flex h-full flex-1 items-stretch md:flex-col">

                                    <div class="flex w-full items-center">
                                        <div
                                            class="overflow-hidden [&amp;:has(textarea:focus)]:border-token-border-xheavy [&amp;:has(textarea:focus)]:shadow-[0_2px_6px_rgba(0,0,0,.05)] flex flex-col w-full dark:border-token-border-heavy flex-grow relative border border-token-border-heavy dark:text-white rounded-2xl bg-white dark:bg-gray-800 shadow-[0_0_0_2px_rgba(255,255,255,0.95)] dark:shadow-[0_0_0_2px_rgba(52,53,65,0.95)]">
                                            <grammarly-extension data-grammarly-shadow-root="true" class="dnXmp"
                                                style="position: absolute; top: -0.833333px; left: -0.833333px; pointer-events: none;"></grammarly-extension>
                                            <grammarly-extension data-grammarly-shadow-root="true" class="dnXmp"
                                                style="position: absolute; top: -0.833333px; left: -0.833333px; pointer-events: none;"></grammarly-extension>
                                            <textarea id="prompt-textarea" tabindex="0" data-id="root" rows="1"
                                                class="m-0 w-full resize-none border-0 bg-transparent py-[10px] pr-10 focus:ring-0 focus-visible:ring-0 dark:bg-transparent md:py-3.5 md:pr-12 placeholder-black/50 dark:placeholder-white/50 pl-3 md:pl-4 form-control min-h-[52px] rounded-full"
                                                spellcheck="true"></textarea>
                                            <input type="hidden" value="{{ $conversation->id ?? '' }}" id="chat_id">
                                            <button id="send_message_button"
                                                class="absolute md:bottom-3 md:right-3 dark:hover:bg-gray-900 dark:disabled:hover:bg-transparent right-2 dark:disabled:bg-white disabled:bg-black disabled:opacity-10 disabled:text-gray-400 enabled:bg-black text-white p-0.5 border border-black rounded-lg dark:border-white dark:bg-white bottom-1.5 transition-colors"
                                                data-testid="send-button">
                                                <span class="" data-state="closed">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" class="text-white dark:text-black">
                                                        <path d="M7 11L12 6L17 11M12 18V7" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </main>
                </div>
            </div>
        </div>
    </div>
    <template id="chat_user_bubble">
        <div class="lqd-chat-user-bubble flex content-start mb-2 lg:ms-auto gap-[8px]">
            <span class="text-dark mt-2">
                <span class="avatar w-[24px] h-[24px] shrink-0"
                    style="background-image: url(/upload/images/avatar/vPg2-plato-data-avatar.png)"></span>
            </span>
            <div
                class="max-w-[calc(100%-64px)] border-none rounded-[2em] mb-[7px] bg-[#F3E2FD] text-[#090A0A] dark:bg-[rgba(var(--tblr-primary-rgb),0.3)] dark:text-white">
                <div class="chat-content py-[0.75rem] px-[1.5rem]">
                </div>
            </div>
        </div>
    </template>

    <template id="chat_ai_bubble">
        <div class="lqd-chat-ai-bubble flex content-start mb-2 gap-[8px] group">
            <div class="h-7 w-7 flex-shrink-0 mt-2">
                <div
                    class="gizmo-shadow-stroke relative flex h-full items-center justify-center rounded-full bg-white text-black">
                    <svg width="41" height="41" viewBox="0 0 41 41" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="h-2/3 w-2/3" role="img">
                        <text x="-9999" y="-9999">ChatGPT</text>
                        <path
                            d="M37.5324 16.8707C37.9808 15.5241 38.1363 14.0974 37.9886 12.6859C37.8409 11.2744 37.3934 9.91076 36.676 8.68622C35.6126 6.83404 33.9882 5.3676 32.0373 4.4985C30.0864 3.62941 27.9098 3.40259 25.8215 3.85078C24.8796 2.7893 23.7219 1.94125 22.4257 1.36341C21.1295 0.785575 19.7249 0.491269 18.3058 0.500197C16.1708 0.495044 14.0893 1.16803 12.3614 2.42214C10.6335 3.67624 9.34853 5.44666 8.6917 7.47815C7.30085 7.76286 5.98686 8.3414 4.8377 9.17505C3.68854 10.0087 2.73073 11.0782 2.02839 12.312C0.956464 14.1591 0.498905 16.2988 0.721698 18.4228C0.944492 20.5467 1.83612 22.5449 3.268 24.1293C2.81966 25.4759 2.66413 26.9026 2.81182 28.3141C2.95951 29.7256 3.40701 31.0892 4.12437 32.3138C5.18791 34.1659 6.8123 35.6322 8.76321 36.5013C10.7141 37.3704 12.8907 37.5973 14.9789 37.1492C15.9208 38.2107 17.0786 39.0587 18.3747 39.6366C19.6709 40.2144 21.0755 40.5087 22.4946 40.4998C24.6307 40.5054 26.7133 39.8321 28.4418 38.5772C30.1704 37.3223 31.4556 35.5506 32.1119 33.5179C33.5027 33.2332 34.8167 32.6547 35.9659 31.821C37.115 30.9874 38.0728 29.9178 38.7752 28.684C39.8458 26.8371 40.3023 24.6979 40.0789 22.5748C39.8556 20.4517 38.9639 18.4544 37.5324 16.8707ZM22.4978 37.8849C20.7443 37.8874 19.0459 37.2733 17.6994 36.1501C17.7601 36.117 17.8666 36.0586 17.936 36.0161L25.9004 31.4156C26.1003 31.3019 26.2663 31.137 26.3813 30.9378C26.4964 30.7386 26.5563 30.5124 26.5549 30.2825V19.0542L29.9213 20.998C29.9389 21.0068 29.9541 21.0198 29.9656 21.0359C29.977 21.052 29.9842 21.0707 29.9867 21.0902V30.3889C29.9842 32.375 29.1946 34.2791 27.7909 35.6841C26.3872 37.0892 24.4838 37.8806 22.4978 37.8849ZM6.39227 31.0064C5.51397 29.4888 5.19742 27.7107 5.49804 25.9832C5.55718 26.0187 5.66048 26.0818 5.73461 26.1244L13.699 30.7248C13.8975 30.8408 14.1233 30.902 14.3532 30.902C14.583 30.902 14.8088 30.8408 15.0073 30.7248L24.731 25.1103V28.9979C24.7321 29.0177 24.7283 29.0376 24.7199 29.0556C24.7115 29.0736 24.6988 29.0893 24.6829 29.1012L16.6317 33.7497C14.9096 34.7416 12.8643 35.0097 10.9447 34.4954C9.02506 33.9811 7.38785 32.7263 6.39227 31.0064ZM4.29707 13.6194C5.17156 12.0998 6.55279 10.9364 8.19885 10.3327C8.19885 10.4013 8.19491 10.5228 8.19491 10.6071V19.808C8.19351 20.0378 8.25334 20.2638 8.36823 20.4629C8.48312 20.6619 8.64893 20.8267 8.84863 20.9404L18.5723 26.5542L15.206 28.4979C15.1894 28.5089 15.1703 28.5155 15.1505 28.5173C15.1307 28.5191 15.1107 28.516 15.0924 28.5082L7.04046 23.8557C5.32135 22.8601 4.06716 21.2235 3.55289 19.3046C3.03862 17.3858 3.30624 15.3413 4.29707 13.6194ZM31.955 20.0556L22.2312 14.4411L25.5976 12.4981C25.6142 12.4872 25.6333 12.4805 25.6531 12.4787C25.6729 12.4769 25.6928 12.4801 25.7111 12.4879L33.7631 17.1364C34.9967 17.849 36.0017 18.8982 36.6606 20.1613C37.3194 21.4244 37.6047 22.849 37.4832 24.2684C37.3617 25.6878 36.8382 27.0432 35.9743 28.1759C35.1103 29.3086 33.9415 30.1717 32.6047 30.6641C32.6047 30.5947 32.6047 30.4733 32.6047 30.3889V21.188C32.6066 20.9586 32.5474 20.7328 32.4332 20.5338C32.319 20.3348 32.154 20.1698 31.955 20.0556ZM35.3055 15.0128C35.2464 14.9765 35.1431 14.9142 35.069 14.8717L27.1045 10.2712C26.906 10.1554 26.6803 10.0943 26.4504 10.0943C26.2206 10.0943 25.9948 10.1554 25.7963 10.2712L16.0726 15.8858V11.9982C16.0715 11.9783 16.0753 11.9585 16.0837 11.9405C16.0921 11.9225 16.1048 11.9068 16.1207 11.8949L24.1719 7.25025C25.4053 6.53903 26.8158 6.19376 28.2383 6.25482C29.6608 6.31589 31.0364 6.78077 32.2044 7.59508C33.3723 8.40939 34.2842 9.53945 34.8334 10.8531C35.3826 12.1667 35.5464 13.6095 35.3055 15.0128ZM14.2424 21.9419L10.8752 19.9981C10.8576 19.9893 10.8423 19.9763 10.8309 19.9602C10.8195 19.9441 10.8122 19.9254 10.8098 19.9058V10.6071C10.8107 9.18295 11.2173 7.78848 11.9819 6.58696C12.7466 5.38544 13.8377 4.42659 15.1275 3.82264C16.4173 3.21869 17.8524 2.99464 19.2649 3.1767C20.6775 3.35876 22.0089 3.93941 23.1034 4.85067C23.0427 4.88379 22.937 4.94215 22.8668 4.98473L14.9024 9.58517C14.7025 9.69878 14.5366 9.86356 14.4215 10.0626C14.3065 10.2616 14.2466 10.4877 14.2479 10.7175L14.2424 21.9419ZM16.071 17.9991L20.4018 15.4978L24.7325 17.9975V22.9985L20.4018 25.4983L16.071 22.9985V17.9991Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
            </div>
            <div
                class="chat-content-container border-none rounded-[2em] mb-[7px] min-h-[44px] max-w-[calc(100%-64px)] text-[#090A0A] relative before:content-[''] before:rounded-[2em] before:inline-block before:bg-[#E5E7EB] before:absolute before:inset-0 group-[&.loading]:before:animate-pulse-intense dark:before:bg-[rgba(255,255,255,0.02)] dark:text-white">
                <div
                    class="lqd-typing !inline-flex !items-center !rounded-full !py-2 !px-3 !gap-3 !leading-none !font-medium">
                    <div class="lqd-typing-dots !flex !items-center !gap-1">
                        <span class="lqd-typing-dot !w-1 !h-1 !rounded-full"></span>
                        <span class="lqd-typing-dot !w-1 !h-1 !rounded-full"></span>
                        <span class="lqd-typing-dot !w-1 !h-1 !rounded-full"></span>
                    </div>
                </div>
                <pre
                    class="chat-content py-[0.75rem] px-[1.5rem] bg-transparent text-inherit font-[inherit] text-[1em] indent-0 m-0 w-full relative whitespace-pre-wrap empty:!hidden"></pre>

                {{-- <button class="btn btn-primary w-[52px] h-[52px] rounded-full shrink-0" id="send_message_button"
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor"
                        width="20">
                        <path
                            d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z" />
                    </svg>
                </button>
                <button class="btn btn-primary w-[52px] h-[52px] rounded-full shrink-0" id="stop_button" type="button">
                    <svg class="rtl:-scale-x-100" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M8 13v-7.5a1.5 1.5 0 0 1 3 0v6.5"></path>
                        <path d="M11 5.5v-2a1.5 1.5 0 1 1 3 0v8.5"></path>
                        <path d="M14 5.5a1.5 1.5 0 0 1 3 0v6.5"></path>
                        <path
                            d="M17 7.5a1.5 1.5 0 0 1 3 0v8.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7a69.74 69.74 0 0 1 -.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47">
                        </path>
                    </svg>
                </button> --}}
            </div>
        </div>
    </template>

    <template id="conversation_items_group">
        <span>
            <div class="relative" data-projection-id="2"
                style="height: auto; opacity: 1; transform: none; transform-origin: 50% 50% 0px;">
                <div data-projection-id="3" style="transform: none; transform-origin: 50% 50% 0px; opacity: 1;">
                    <h3
                        class="h-9 pb-2 pt-3 px-2 text-xs font-medium text-ellipsis overflow-hidden break-all bg-white dark:bg-black text-gizmo-gray-600 group-title">
                        --GroupTitle--</h3>
                </div>
                <ol>

                </ol>
            </div>

        </span>
    </template>
    <template id="conversation_item">
        <li class="relative z-[15]" data-projection-id="--projectionId--"
            style="opacity: 1; height: auto; transform: none; transform-origin: 50% 50% 0px;">
            <div class="group relative active:opacity-90">
                <div class="flex items-center gap-2 rounded-lg p-2 hover:bg-token-surface-primary justify-content-between">
                    <a class="w-50 overflow-hidden chat-link" href="--route--"
                        class="flex items-center gap-2 rounded-lg p-2 hover:bg-token-surface-primary chat-link">
                        <div class="relative grow overflow-hidden whitespace-nowrap editable-title conversation-title"
                            contenteditable="true">--chatName--
                        </div>
                    </a>
                    <!-- Add delete and edit buttons -->
                    <div>
                        <button class="btn w-[36px] h-[36px] p-0 border hover:bg-[var(--tblr-primary)] hover:text-white"
                            onclick="deleteConversation(this)">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.08789 1.74609L5.80664 5L9.08789 8.25391L8.26758 9.07422L4.98633 5.82031L1.73242 9.07422L0.912109 8.25391L4.16602 5L0.912109 1.74609L1.73242 0.925781L4.98633 4.17969L8.26758 0.925781L9.08789 1.74609Z">
                                </path>
                            </svg>
                        </button>
                        <button class="btn w-[36px] h-[36px] p-0 border hover:bg-[var(--tblr-primary)] hover:text-white"
                            onclick="editConversationTitle(this)"> <svg width="13" height="12"
                                viewBox="0 0 16 15" fill="none" stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.3125 2.55064L12.8125 5.94302M11.5 12.3038H15M4.5 14L13.6875 5.09498C13.9173 4.87223 14.0996 4.60779 14.224 4.31676C14.3484 4.02572 14.4124 3.71379 14.4124 3.39878C14.4124 3.08377 14.3484 2.77184 14.224 2.48081C14.0996 2.18977 13.9173 1.92533 13.6875 1.70259C13.4577 1.47984 13.1849 1.30315 12.8846 1.1826C12.5843 1.06205 12.2625 1 11.9375 1C11.6125 1 11.2907 1.06205 10.9904 1.1826C10.6901 1.30315 10.4173 1.47984 10.1875 1.70259L1 10.6076V14H4.5Z"
                                    stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </li>
    </template>

@endsection
@section('script')
    <script>
        setTimeout(function() {
            const generateBtn = document.getElementById("send_message_button");
            const stopBtn = document.getElementById("stop_button");
            const promptInput = document.getElementById("prompt-textarea");
            const guest_id = document.getElementById("guest_id").value;
            const guest_event_id = document.getElementById("guest_event_id").value;
            const guest_look_id = document.getElementById("guest_look_id").value;
            const guest_product_id = document.getElementById("guest_product_id").value;
            const openai_model = '{!! $setting->openai_default_model !!}';

            let controller = null; // Store the AbortController instance
            let scrollLocked = false;
            let nIntervId = null;
            let chunk = [];
            let streaming = true;
            let messages = [];
            let result = '';
            let stream_type = 'frontend';

            const generate = async (ev) => {
                "use strict";
                ev?.preventDefault();
                // Alert the user if no prompt value
                const promptInputValue = promptInput.value;
                if (!promptInputValue || promptInputValue.length === 0 || promptInputValue.replace(/\s/g,
                        '') === '') {
                    return toastr.error('Please fill the message field.');
                }

                const chatsContainer = $(".chats-container");
                const userBubbleTemplate = document.querySelector('#chat_user_bubble').content.cloneNode(
                    true);
                const aiBubbleTemplate = document.querySelector('#chat_ai_bubble').content.cloneNode(true);

                const prompt1 = atob(guest_event_id);
                const prompt2 = atob(guest_look_id);
                const prompt3 = atob(guest_product_id);

                const chat_id = $('#chat_id').val();
                if (chat_id == "") {
                    $("#home_suggestions").remove();
                }
                const bearer = prompt1 + prompt2 + prompt3;
                // Disable the generate button and enable the stop button
                generateBtn.disabled = true;
                generateBtn.classList.add('submitting');
                // //stopBtn.disabled = false;
                userBubbleTemplate.querySelector('.chat-content').innerHTML = promptInputValue;
                promptInput.value = '';
                chatsContainer.append(userBubbleTemplate);

                // Create a new AbortController instance
                controller = new AbortController();
                const signal = controller.signal;

                let responseText = '';
                const aiBubbleWrapper = aiBubbleTemplate.firstElementChild;
                aiBubbleWrapper.classList.add('loading');
                aiBubbleTemplate.querySelector('.chat-content').innerHTML = responseText;
                chatsContainer.append(aiBubbleTemplate);
                chatsContainer[0].scrollTo(0, chatsContainer[0].scrollHeight);
                messages.push({
                    role: "user",
                    content: promptInputValue
                });

                function onBeforePageUnload(e) {
                    e.preventDefault();
                    e.returnValue = '';
                }

                function onWindowScroll() {
                    if (chatsContainer[0].scrollTop + chatsContainer[0].offsetHeight >= chatsContainer[0]
                        .scrollHeight) {
                        scrollLocked = true;
                    } else {
                        scrollLocked = false;
                    }
                }

                let guest_id2 = atob(guest_id);

                chunk = [];
                streaming = true;
                result = '';
                nIntervId = setInterval(function() {
                    if (chunk.length == 0 && !streaming) {
                        messages.push({
                            role: "assistant",
                            content: result
                        });

                        if (messages.length >= 6) {
                            messages.splice(1, 2);
                            console.log("after completion");
                        }

                        saveResponse(promptInputValue, aiBubbleWrapper.querySelector(
                            '.chat-content').innerHTML, chat_id)


                        generateBtn.disabled = false;
                        generateBtn.classList.remove('submitting');
                        aiBubbleWrapper.classList.remove('loading');
                        //stopBtn.disabled = true;
                        controller = null; // Reset the AbortController instance

                        jQuery(".chats-container").stop().animate({
                            scrollTop: jQuery(".chats-container")[0]?.scrollHeight
                        }, 200);
                        jQuery("#scrollable_content").stop().animate({
                            scrollTop: jQuery("#scrollable_content").outerHeight()
                        }, 200);

                        window.removeEventListener('beforeunload', onBeforePageUnload);
                        chatsContainer[0].removeEventListener('scroll', onWindowScroll);
                        clearInterval(nIntervId);
                    }

                    const text = chunk.shift();
                    if (text) {
                        aiBubbleWrapper.classList.remove('loading');
                        aiBubbleWrapper.querySelector('.chat-content').innerHTML += text;
                        chatsContainer[0].scrollTo(0, chatsContainer[0].scrollHeight);
                    }

                }, 20);

                // if (stream_type == 'backend') {
                //     const eventSource = new EventSource(
                //         `${ streamUrl }/?message=${promptInputValue}&category=${category.id}`);

                //     eventSource.addEventListener('data', function(event) {
                //         const data = JSON.parse(event.data);
                //         if (data.message !== null)
                //             chunk.push(data.message);
                //     });

                //     // finished eventSource
                //     eventSource.addEventListener('stop', function(event) {
                //         streaming = false;
                //         eventSource.close();
                //     });

                //     // error handler for eventSource
                //     eventSource.addEventListener('error', (event) => {
                //         console.log(event);
                //         aiBubbleWrapper.querySelector('.chat-content').innerHTML =
                //             "Api Connection Error."
                //         eventSource.close()
                //         clearInterval(nIntervId)
                //         generateBtn.disabled = false;
                //         generateBtn.classList.remove('submitting');
                //         aiBubbleWrapper.classList.remove('loading');
                //         document.getElementById("chat_form").reset();
                //         streaming = false
                //         messages.pop()
                //     })
                // } else {
                try {

                    // Fetch the response from the OpenAI API with the signal from AbortController
                    const response = await fetch(guest_id2, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${bearer}`,
                        },
                        body: JSON.stringify({
                            model: openai_model,
                            messages: [...(messages.slice(0, messages.length - 1)),
                                messages[messages.length - 1]
                            ],
                            max_tokens: 2000,
                            stream: true, // For streaming responses
                        }),
                        signal, // Pass the signal to the fetch request
                    });

                    if (response.status != 200) {
                        throw response;
                    }
                    // Read the response as a stream of data
                    const reader = response.body.getReader();
                    const decoder = new TextDecoder("utf-8");
                    let result = '';

                    while (true) {
                        // if ( window.console || window.console.firebug ) {
                        // 	console.clear();
                        // }
                        const {
                            done,
                            value
                        } = await reader.read();
                        if (done) {
                            streaming = false;
                            break;
                        }
                        // Massage and parse the chunk of data
                        const chunk1 = decoder.decode(value);
                        const lines = chunk1.split("\n");

                        const parsedLines = lines
                            .map((line) => line.replace(/^data: /, "")
                                .trim()) // Remove the "data: " prefix
                            .filter((line) => line !== "" && line !==
                                "[DONE]") // Remove empty lines and "[DONE]"
                            .map((line) => {
                                try {
                                    return JSON.parse(line);
                                } catch (ex) {
                                    console.log(line);
                                }
                                return null;
                            }); // Parse the JSON string

                        for (const parsedLine of parsedLines) {
                            if (!parsedLine) continue;
                            const {
                                choices
                            } = parsedLine;
                            const {
                                delta
                            } = choices[0];
                            const {
                                content
                            } = delta;
                            // const { finish_reason } = choices[0];

                            if (content) {
                                chunk.push(content);
                            }
                        }
                    }

                } catch (error) {
                    // Handle fetch request errors
                    if (signal.aborted) {
                        console.log("Request aborted by user. Not saved.");
                    } else {
                        switch (error.status) {
                            case 429:
                                console.log(
                                    "Api Connection Error. You hit the rate limites of openai requests. Please check your Openai API Key."
                                );
                                break;
                            default:
                                console.log(
                                    "Api Connection Error. Please contact system administrator via Support Ticket. Error is: API Connection failed due to API keys."
                                );
                        }

                    }
                    clearInterval(nIntervId)
                    streaming = false
                    messages.pop()
                }
                //}
            };

            const stop = () => {
                // Abort the fetch request by calling abort() on the AbortController instance
                if (controller) {
                    controller.abort();
                    controller = null;
                    chunk = [];
                    streaming = false;
                }
            };
            promptInput.addEventListener('keypress', ev => {
                if (ev.keyCode == 13) {
                    ev.preventDefault();
                    return generate();
                }
            });

            generateBtn.addEventListener("click", generate);
            // //stopBtn.addEventListener( "click", stop );

            // Get all buttons with the class "suggestion-button"
            var buttons = document.querySelectorAll('.suggestion-button');

            // Add event listener to each button
            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    getButtonText(this);
                });
            });

            // Function to get the text of the clicked button
            // Function to get the text of the clicked button
            function getButtonText(button) {
                var buttonText = button.textContent || button.innerText;
                promptInput.value = buttonText;
                return generate();
            }

        }, 100);

        function saveResponse(input, response, chat_id) {
            "use strict";
            var formData = new FormData();
            formData.append('chat_id', chat_id);
            formData.append('input', input);
            formData.append('response', response);
            jQuery.ajax({
                url: '/dashboard/user/zeusai/chat_save',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(responseData) {
                    if (responseData.newConversation) {
                        console.log(responseData.chat_id);
                        $("#chat_id").val(responseData.chat_id);
                        // Prepend a new group with the chat name to the conversation container
                        //prependChatGroup(responseData.title);

                    }
                },
                error: function(error) {
                    // Handle error response here
                    console.error("Error:", error);
                }
            });
            return false;
        }

        // Function to prepend a new chat group to the container
        // function prependChatGroup(chatName) {
        //     const container = document.getElementById('all_conversations');

        //     // Clone the group template
        //     let groupTemplate = document.getElementById('today_conversations').content.cloneNode(true);
        //     console.log(groupTemplate.innerHTML);
        //     // Modify   the content of the cloned group template based on the chat name
        //     let groupTitle = groupTemplate.querySelector('.group-title');
        //     groupTitle.textContent = chatName;

        //     // Prepend the group template to the container
        //     container.insertBefore(groupTemplate, container.firstChild);
        // }
    </script>



    <script>
        let page = 1; // Initialize the page counter
        let isLoading = false; // Flag to track whether a request is in progress
        var allConversationsDiv = $('#all_conversations');
        // Function to fetch conversations and append them to the container
        function fetchConversations(page) {
            
            $.ajax({
                url: '/dashboard/user/zeusai/all_conversations',
                method: 'GET',
                data: {
                    page: page
                },
                dataType: 'json',
                success: function(data) {
                    var groupedConversations = groupByDate(data.data);

                    // Update the content of the 'all_conversations' div with the grouped conversations
                    updateConversations(groupedConversations);
                    isLoading = false; // Reset the loading flag
                   

                },
                error: function(error) {
                    console.error('Error fetching conversations:', error);
                    isLoading = false; // Reset the loading flag

                }
            });
        }

        function groupByDate(conversations) {
            var grouped = {
                'Today': [],
                'Yesterday': [],
                'Last 7 Days': [],
                'All': []
            };

            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var yesterday = new Date(today);
            yesterday.setDate(today.getDate() - 1);

            var last7Days = new Date(today);
            last7Days.setDate(today.getDate() - 8);

            conversations.forEach(function(conversation) {
                var conversationDate = new Date(conversation.created_at).setHours(0, 0, 0, 0);

                if (conversationDate === today.getTime()) {
                    grouped['Today'].push(conversation);
                } else if (conversationDate === yesterday.getTime()) {
                    grouped['Yesterday'].push(conversation);
                } else if (conversationDate >= last7Days.getTime()) {
                    grouped['Last 7 Days'].push(conversation);
                } else {
                    grouped['All'].push(conversation);
                }
            });

            return grouped;
        }

        // Function to update the content of the 'all_conversations' div
        function updateConversations(groupedConversations) {
            // Loop through each group
            for (var group in groupedConversations) {
                if (groupedConversations.hasOwnProperty(group) && groupedConversations[group].length > 0) {
                    // Check if a group with the same title already exists
                    var existingGroup = allConversationsDiv.find('div:contains("' + group + '")');
                    var groupOl;
                    
                    if (existingGroup.length > 0) {
                        // If the group exists, use its ol
                        console.log(existingGroup)
                        groupOl = existingGroup.find('ol');
                        console.log(groupOl,group);
                    } else {
                        // If the group doesn't exist, create a new group
                        var groupHtml = $('#conversation_items_group').html();
                        groupHtml = groupHtml.replace('--GroupTitle--', group);

                        // Append the group HTML to the 'all_conversations' div
                        allConversationsDiv.append(groupHtml);

                        // Reference to the ol within the current group
                        groupOl = allConversationsDiv.find('ol').last();
                        console.log("create new group", group)
                    }
                    console.log(groupOl);
                    // Loop through conversations for the current group and append them to the ol
                    groupedConversations[group].forEach(function (conversation) {
                        // Clone the conversation_item template
                        var itemHtml = $('#conversation_item').html();
                        itemHtml = itemHtml.replace('--chatName--', conversation.title);
                        itemHtml = itemHtml.replace('--projectionId--', conversation.id);
                        itemHtml = itemHtml.replace('--route--', '{{ route('dashboard.user.zeusai.chat') }}' +
                            '/' + conversation.id);

                        // Append the updated item HTML to the ol within the current group
                        groupOl.append(itemHtml);
                    });
                }
            }
        }
        

        function isScrollAtBottom() {
            var scrollHeight = allConversationsDiv[0].scrollHeight;
            var scrollPosition = allConversationsDiv.height() + allConversationsDiv.scrollTop() + 50;
            return (scrollPosition >= scrollHeight);
        }

        allConversationsDiv.on('scroll', function() {
            if (isScrollAtBottom()) { 
                if (isLoading) {
                return; // If a request is already in progress, do nothing
            }

            isLoading = true; // Set the loading flag to true
            page++;              
            fetchConversations(page);
            }
        });
        // Function to delete conversation
        function deleteConversation(button) {
            // Assuming your conversation ID is stored as a data attribute on the button
            var conversationId = button.closest('li').dataset.projectionId;

            // Perform AJAX request to delete conversation
            fetch(`/dashboard/user/zeusai/delete/${conversationId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        // Add any additional headers as needed
                    },
                    // Add any additional options as needed
                })
                .then(response => {
                    if (response.ok) {
                        // Remove the conversation item from the UI
                        button.closest('li').remove();
                        var currentChatId = $("#chat_id").val();
                        if (currentChatId == conversationId) {
                            // Redirect to a new location (open a new chat window)
                            window.location.href =
                                '{{ route('dashboard.user.zeusai.chat') }}'; // Replace with your new chat route
                        }

                    } else {
                        console.error('Failed to delete conversation');
                    }
                })
                .catch(error => {
                    console.error('Error during delete conversation request:', error);
                });
        }

        function editConversationTitle(button) {
            var titleElement = button.closest('li').querySelector('.editable-title');
            titleElement.contentEditable = true;

            // Select the text in the title element
            selectText(titleElement);

            // Add event listener for Enter key
            titleElement.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent newline character
                    handleTitleUpdate(titleElement, button);
                    selection.removeAllRanges();

                    titleElement.contentEditable = false;
                }
            });

            // Add focus to the editable title
            titleElement.focus();
        }

        // Function to select text in an element
        function selectText(element) {
            var range = document.createRange();
            range.selectNodeContents(element);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }


        // Function to handle title update
        function handleTitleUpdate(titleElement, button) {
            var newTitle = titleElement.textContent.trim();

            if (newTitle !== '') {
                // Update the UI with the new title
                var currentTitle = titleElement.dataset.currentTitle;
                if (newTitle !== currentTitle) {
                    titleElement.dataset.currentTitle = newTitle;

                    // Assuming your conversation ID is stored as a data attribute on the button
                    var conversationId = button.closest('li').dataset.projectionId;

                    // Perform AJAX request to update conversation title
                    // Replace '/update-conversation-title' with your actual update route
                    // You may need to include CSRF token in your AJAX request
                    fetch(`/dashboard/user/zeusai/update/${conversationId}`, {
                            method: 'PATCH', // or 'PUT' depending on your route definition
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                // Add any additional headers as needed
                            },
                            body: JSON.stringify({
                                title: newTitle,
                            }),
                            // Add any additional options as needed
                        })
                        .then(response => {
                            if (!response.ok) {
                                console.error('Failed to update conversation title');
                                // Revert the UI to the original title if the update fails
                                titleElement.textContent = currentTitle;
                            }
                        })
                        .catch(error => {
                            console.error('Error during update conversation title request:', error);
                            // Revert the UI to the original title if there's an error
                            titleElement.textContent = currentTitle;
                        });
                }
            }
        }


        // Initial fetch
        fetchConversations(page);

        jQuery(".chats-container").stop().animate({
            scrollTop: jQuery(".chats-container")[0]?.scrollHeight
        }, 200);
        jQuery("#scrollable_content").stop().animate({
            scrollTop: jQuery("#scrollable_content").outerHeight()
        }, 200);

        document.getElementById('prompt-textarea').addEventListener('keydown', function(e) {
            if (e.keyCode === 13 && e.shiftKey) {
                e.preventDefault();
                var s = this.selectionStart;
                this.value = this.value.substring(0, this.selectionStart) + "\n" + this.value.substring(this
                    .selectionEnd);
                this.selectionStart = this.selectionEnd = s + 1;
            }
        });
    </script>

@endsection
