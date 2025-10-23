<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                @layer theme{
                  :root,:host{
                    --font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
                  }
                }
                @layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}/* ... (biarkan isi tailwind default ini apa adanya) ... */}
                @layer components;
                @layer utilities{}
                @keyframes spin{to{transform:rotate(360deg)}}
                @keyframes ping{75%,to{opacity:0;transform:scale(2)}}
                @keyframes pulse{50%{opacity:.5}}
                @keyframes bounce{0%,to{animation-timing-function:cubic-bezier(.8,0,1,1);transform:translateY(-25%)}50%{animation-timing-function:cubic-bezier(0,0,.2,1);transform:none}}
                @property --tw-translate-x{syntax:"*";inherits:false;initial-value:0}
                @property --tw-content{syntax:"*";inherits:false;initial-value:""}
            </style>
        @endif
    </head>
    <body class="bg-[#FFFAEB] dark:bg-[#1A1411] text-[#5B4636] dark:text-[#EDE3DB] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#F7EFE9] border-[#EADFCC66] hover:border-[#EADFCCB3] border text-[#5B4636] dark:border-[#6B5A4F] dark:hover:border-[#8A7468] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#F7EFE9] text-[#5B4636] border border-transparent hover:border-[#EADFCC66] dark:hover:border-[#E3A8AF] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#F7EFE9] border-[#EADFCC66] hover:border-[#EADFCCB3] border text-[#5B4636] dark:border-[#6B5A4F] dark:hover:border-[#E3A8AF] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161210] dark:text-[#F7EFE9] shadow-[inset_0px_0px_0px_1px_rgba(234,223,204,0.35)] dark:shadow-[inset_0px_0px_0px_1px_#6b5a4f] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <h1 class="mb-1 font-medium">Let's get started</h1>
                    <p class="mb-2 text-[#7A695B] dark:text-[#CDBFB4]">Laravel has an incredibly rich ecosystem. <br>We suggest starting with the following.</p>
                    <ul class="flex flex-col mb-4 lg:mb-6">
                        <li class="flex items-center gap-4 py-2 relative before:border-l before:border-[#EADFCC] dark:before:border-[#3E312B] before:top-1/2 before:bottom-0 before:left-[0.4rem] before:absolute">
                            <span class="relative py-1 bg-white dark:bg-[#161210]">
                                <span class="flex items-center justify-center rounded-full bg-[#FFFAEB] dark:bg-[#161210] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E312B] border-[#EADFCC]">
                                    <span class="rounded-full bg-[#EADFCC] dark:bg-[#3E312B] w-1.5 h-1.5"></span>
                                </span>
                            </span>
                            <span>
                                Read the
                                <a href="https://laravel.com/docs" target="_blank" class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-[#FCC0C5] dark:text-[#E3A8AF] ml-1">
                                    <span>Documentation</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-2.5 h-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li class="flex items-center gap-4 py-2 relative before:border-l before:border-[#EADFCC] dark:before:border-[#3E312B] before:bottom-1/2 before:top-0 before:left-[0.4rem] before:absolute">
                            <span class="relative py-1 bg-white dark:bg-[#161210]">
                                <span class="flex items-center justify-center rounded-full bg-[#FFFAEB] dark:bg-[#161210] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E312B] border-[#EADFCC]">
                                    <span class="rounded-full bg-[#EADFCC] dark:bg-[#3E312B] w-1.5 h-1.5"></span>
                                </span>
                            </span>
                            <span>
                                Watch video tutorials at
                                <a href="https://laracasts.com" target="_blank" class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-[#FCC0C5] dark:text-[#E3A8AF] ml-1">
                                    <span>Laracasts</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-2.5 h-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                    </ul>
                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a href="https://cloud.laravel.com" target="_blank" class="inline-block dark:bg-[#E3A8AF] dark:border-[#E3A8AF] dark:text-[#1C1A18] dark:hover:bg-[#F1C6CC] dark:hover:border-[#F1C6CC] hover:bg-[#FCC0C5] hover:border-[#FCC0C5] px-5 py-1.5 bg-[#FCC0C5] rounded-sm border border-[#FCC0C5] text-white text-sm leading-normal">
                                Deploy now
                            </a>
                        </li>
                    </ul>
                </div>

<div class="bg-[#FFF6E7] dark:bg-[#3B2F2A] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
    {{-- Laravel Logo --}}
    <svg class="w-full text-[#FCC0C5] dark:text-[#E3A8AF] transition-all translate-y-0 opacity-100 max-w-none duration-750 starting:opacity-0 starting:translate-y-6" viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="currentColor" />
        <path d="M110.256 41.6337..." fill="currentColor" />
        <path d="M242.805 41.6337..." fill="currentColor" />
        <path d="M438 -3H421.694V102.197H438V-3Z" fill="currentColor" />
        <path d="M139.43 102.197..." fill="currentColor" />
        <path d="M324.49 32.1665..." fill="currentColor" />
        <path d="M376.571 30.3656..." fill="currentColor" />
    </svg>

    {{-- Light Mode 12 SVG --}}
    <svg class="w-[448px] max-w-none relative -mt-[4.9rem] -ml-8 lg:ml-0 lg:-mt-[6.6rem] dark:hidden" viewBox="0 0 440 376" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <!-- ganti nuansa biru -> pastel nude/pink/beige -->
            <path d="M188.263 355.73..." fill="#F3E3D7" />
            <path d="M9.11884 226.339..." fill="#F7EAD9" />
            <path d="M188.263 355.73..." stroke="#EADFCC" stroke-width="1" />
            <path d="M9.11884 226.339..." stroke="#EADFCC" stroke-width="1" />
            <path d="M204.592 327.449..." fill="#F6DADF" />
            <path d="M25.447 198.058..." fill="#F6DADF" />
            <path d="M204.592 327.449..." stroke="#EADFCC" stroke-width="1" />
            <path d="M25.447 198.058..." stroke="#EADFCC" stroke-width="1" />
        </g>
        <g style="mix-blend-mode: hard-light" class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <path d="M217.342 305.363..." fill="#FFF6E7" />
            <path d="M38.197 175.972..." fill="#FFF6E7" />
            <path d="M217.342 305.363..." stroke="#EADFCC" stroke-width="1" />
            <path d="M38.197 175.972..." stroke="#EADFCC" stroke-width="1" />
        </g>
        <g style="mix-blend-mode: plus-darker" class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <path d="M230.951 281.792..." fill="#EBDAC7" />
            <path d="M51.8063 152.402..." fill="#EBDAC7" />
            <path d="M230.951 281.792..." stroke="#EADFCC" stroke-width="1" />
            <path d="M51.8063 152.402..." stroke="#EADFCC" stroke-width="1" />
        </g>
        <g class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <!-- semua stroke -> beige (#EADFCC) -->
            <path d="M188.467 355.363..." stroke="#EADFCC" stroke-width="1" stroke-linejoin="bevel" />
            <path d="M9.32197 225.972..." stroke="#EADFCC" stroke-width="1" stroke-linejoin="bevel" />
            <path d="M345.247 111.915..." stroke="#EADFCC" stroke-width="1" stroke-linejoin="bevel" />
            <!-- (lanjutan path sama, warna stroke = #EADFCC) -->
        </g>
        <g style="mix-blend-mode: hard-light" class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <path d="M246.544 254.79..." fill="#F9EFD6" />
            <path d="M246.544 254.79..." stroke="#EADFCC" stroke-width="1" stroke-linejoin="round" />
        </g>
        <g style="mix-blend-mode: hard-light" class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <path d="M67.41 125.402..." fill="#F9EFD6" />
            <path d="M67.41 125.402..." stroke="#EADFCC" stroke-width="1" />
        </g>
    </svg>

    {{-- Dark Mode 12 SVG --}}
    <svg class="w-[448px] max-w-none relative -mt-[4.9rem] -ml-8 lg:ml-0 lg:-mt-[6.6rem] hidden dark:block" viewBox="0 0 440 376" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
            <path d="M188.263 355.73..." fill="#6B5A4F"/>
            <path d="M9.11884 226.339..." fill="#6B5A4F"/>
            <path d="M188.263 355.73..." stroke="#E3A8AF" stroke-width="1"/>
            <path d="M9.11884 226.339..." stroke="#E3A8AF" stroke-width="1"/>
            <path d="M204.592 327.449..." fill="#8A7468"/>
            <path d="M25.447 198.058..." fill="#8A7468"/>
            <path d="M204.592 327.449..." stroke="#E3A8AF" stroke-width="1"/>
            <path d="M25.447 198.058..." stroke="#E3A8AF" stroke-width="1"/>
        </g>
        <g class="transition-all..." style="mix-blend-mode:hard-light">
            <path d="M217.342 305.363..." fill="#4A3B34"/>
            <path d="M38.197 175.972..." fill="#4A3B34"/>
            <path d="M217.342 305.363..." stroke="#E3A8AF" stroke-width="1"/>
            <path d="M38.197 175.972..." stroke="#E3A8AF" stroke-width="1"/>
        </g>
        <g class="transition-all..." >
            <path d="M217.342 305.363..." stroke="#E3A8AF" stroke-width="1"/>
            <path d="M38.197 175.972..." stroke="#E3A8AF" stroke-width="1"/>
        </g>
        <g class="transition-all...">
            <!-- semua stroke -> pink hangat untuk kontras dark -->
            <path d="M188.467 355.363..." stroke="#E3A8AF" stroke-width="1" stroke-linejoin="bevel"/>
            <!-- (lanjutan path stroke sama seperti di atas, #E3A8AF) -->
        </g>
        <g class="transition-all..." style="mix-blend-mode:hard-light">
            <path d="M246.544 254.79..." fill="#5A4841"/>
            <path d="M246.544 254.79..." stroke="#E3A8AF" stroke-width="1" stroke-linejoin="round"/>
        </g>
        <g class="transition-all..." style="mix-blend-mode:hard-light">
            <path d="M67.41 125.402..." fill="#5A4841"/>
            <path d="M67.41 125.402..." stroke="#E3A8AF" stroke-width="1"/>
        </g>
    </svg>
    <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(234,223,204,0.35)] dark:shadow-[inset_0px_0px_0px_1px_#6b5a4f]"></div>
</div>
</main>
</div>

@if (Route::has('login'))
<div class="h-14.5 hidden lg:block"></div>
@endif
</body>
</html>
