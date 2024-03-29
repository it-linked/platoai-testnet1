<!-- Start image generator -->
@if($openai->type == 'voiceover')
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card bg-[#F2F1FD] !shadow-sm dark:bg-[#14171C] dark:shadow-black">
                <div class="card-body md:p-10">
                    <div class="row">
                        <form id="openai_generator_form" class="workbook-form" onsubmit="return generateSpeech();">
                            <div class="relative mb-3">

                                <input
                                    type="text"
                                    id="workbook_title"
                                    class="form-control rounded-md"
                                    placeholder="{{__('Untitled Document...')}}"
                                    value="{{__('Untitled Document...')}}"
                                    required
                                >

                                <div class="absolute right-0 top-0 hidden">
                                    <a href="#advanced-settings" class="flex items-center text-[11px] font-semibold text-heading hover:no-underline group collapsed" data-bs-toggle="collapse" data-bs-auto-close="false">
                                        {{__('Advanced Settings')}}
                                        <span class="inline-flex items-center justify-center w-[36px] h-[36px] p-0 !ms-2 bg-white !shadow-sm rounded-full dark:!bg-[--tblr-bg-surface]">
                                            <svg class="hidden group-[&.collapsed]:block" width="12" height="12" viewBox="0 0 12 12" fill="var(--lqd-heading-color)" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.76708 5.464H11.1451V7.026H6.76708V11.558H5.18308V7.026H0.805078V5.464H5.18308V0.909999H6.76708V5.464Z"/>
                                            </svg>
                                            <svg class="block group-[&.collapsed]:hidden" width="6" height="2" viewBox="0 0 6 2" fill="var(--lqd-heading-color)" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.335078 1.962V0.246H5.65908V1.962H0.335078Z"/>
                                            </svg>
                                        </span>
                                    </a>
                                </div>

                            </div>
                            <hr>
                            <div class="flex flex-wrap justify-between gap-3 mt-8">
                                <div class="grow">
                                    <label for="language" class="form-label text-heading">
                                        {{__('Language')}}
                                        <x-info-tooltip text="{{__('Select speech language')}}" />
                                    </label>
                                    <select name="languages" id="languages" class="form-control form-select bg-[#fff] placeholder:text-black">
                                        <option @if(LaravelLocalization::getCurrentLocale() == "af") {{ 'selected' }} @endif value="af-ZA">{{__('Afrikaans (South Africa)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ar") {{ 'selected' }} @endif value="ar-XA">{{__('Arabic')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "eu") {{ 'selected' }} @endif value="eu-ES">{{__('Basque (Spain)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "bn") {{ 'selected' }} @endif value="bn-IN">{{__('Bengali (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "bg") {{ 'selected' }} @endif value="bg-BG">{{__('Bulgarian (Bulgaria)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ca") {{ 'selected' }} @endif value="ca-ES">{{__('Catalan (Spain) ')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "yue") {{ 'selected' }} @endif value="yue-HK">{{__('Chinese (Hong Kong)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "cs") {{ 'selected' }} @endif value="cs-CZ">{{__('Czech (Czech Republic)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "da") {{ 'selected' }} @endif value="da-DK">{{__('Danish (Denmark)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "nl") {{ 'selected' }} @endif value="nl-BE">{{__('Dutch (Belgium)')}}</option>
                                        <option value="nl-NL">{{__('Dutch (Netherlands)')}}</option>
                                        <option value="en-AU">{{__('English (Australia)')}}</option>
                                        <option value="en-IN">{{__('English (India)')}}</option>
                                        <option value="en-GB">{{__('English (UK)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "en") {{ 'selected' }} @endif value="en-US">{{__('English (US)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "fil") {{ 'selected' }} @endif value="fil-PH">{{__('Filipino (Philippines)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "fi") {{ 'selected' }} @endif value="fi-FI">{{__('Finnish (Finland)')}}</option>
                                        <option value="fr-CA">{{__('French (Canada)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "fr") {{ 'selected' }} @endif value="fr-FR">{{__('French (France)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "gl") {{ 'selected' }} @endif value="gl-ES">{{__('Galician (Spain)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "de") {{ 'selected' }} @endif value="de-DE">{{__('German (Germany)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "el") {{ 'selected' }} @endif value="el-GR">{{__('Greek (Greece)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "gu") {{ 'selected' }} @endif value="gu-IN">{{__('Gujarati (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "he") {{ 'selected' }} @endif value="he-IL">{{__('Hebrew (Israel)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "hi") {{ 'selected' }} @endif value="hi-IN">{{__('Hindi (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "hu") {{ 'selected' }} @endif value="hu-HU">{{__('Hungarian (Hungary)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "is") {{ 'selected' }} @endif value="is-IS">{{__('Icelandic (Iceland)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "id") {{ 'selected' }} @endif value="id-ID">{{__('Indonesian (Indonesia)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "it") {{ 'selected' }} @endif value="it-IT">{{__('Italian (Italy)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ja") {{ 'selected' }} @endif value="ja-JP">{{__('Japanese (Japan)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "kn") {{ 'selected' }} @endif value="kn-IN">{{__('Kannada (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ko") {{ 'selected' }} @endif value="ko-KR">{{__('Korean (South Korea)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "lv") {{ 'selected' }} @endif value="lv-LV">{{__('Latvian (Latvia)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ms") {{ 'selected' }} @endif value="ms-MY">{{__('Malay (Malaysia)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ml") {{ 'selected' }} @endif value="ml-IN">{{__('Malayalam (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "cmn") {{ 'selected' }} @endif value="cmn-CN">{{__('Mandarin Chinese')}}</option>
                                        <option value="cmn-TW">{{__('Mandarin Chinese (T)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "mr") {{ 'selected' }} @endif value="mr-IN">{{__('Marathi (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "nb") {{ 'selected' }} @endif value="nb-NO">{{__('Norwegian (Norway)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "pl") {{ 'selected' }} @endif value="pl-PL">{{__('Polish (Poland)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "pt") {{ 'selected' }} @endif value="pt-BR">{{__('Portuguese (Brazil)')}}</option>
                                        <option value="pt-PT">{{__('Portuguese (Portugal)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "pa") {{ 'selected' }} @endif value="pa-IN">{{__('Punjabi (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ro") {{ 'selected' }} @endif value="ro-RO">{{__('Romanian (Romania)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ru") {{ 'selected' }} @endif value="ru-RU">{{__('Russian (Russia)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "sr") {{ 'selected' }} @endif value="sr-RS">{{__('Serbian (Cyrillic)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "sk") {{ 'selected' }} @endif value="sk-SK">{{__('Slovak (Slovakia)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "es") {{ 'selected' }} @endif value="es-ES">{{__('Spanish (Spain)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "es") {{ 'selected' }} @endif value="es-US">{{__('Spanish (US)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "sv") {{ 'selected' }} @endif value="sv-SE">{{__('Swedish (Sweden)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ta") {{ 'selected' }} @endif value="ta-IN">{{__('Tamil (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "te") {{ 'selected' }} @endif value="te-IN">{{__('Telugu (India)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "th") {{ 'selected' }} @endif value="th-TH">{{__('Thai (Thailand)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "tr") {{ 'selected' }} @endif value="tr-TR">{{__('Turkish (Turkey)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "uk") {{ 'selected' }} @endif value="uk-UA">{{__('Ukrainian (Ukraine)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "ur") {{ 'selected' }} @endif value="ur-PK">{{__('Urdu (Pakistan)')}}</option>
                                        <option @if(LaravelLocalization::getCurrentLocale() == "vi") {{ 'selected' }} @endif value="vi-VN">{{__('Vietnamese (Vietnam)')}}</option>
                                    </select>
                                </div>
                                <div class="grow">
                                    <label for="voice" class="form-label text-heading">
                                        {{__('Voice')}}
                                        <x-info-tooltip text="{{__('You can select speech voice. Female, Male, and type')}}" />
                                    </label>
                                    <select id="voice" name="voice" class="form-control form-select bg-[#fff] placeholder:text-black">
                                        <option value="">{{ __('Select a voice') }}</option>
                                    </select>
                                </div>
                                <div class="grow">
                                    <label for="pace" class="form-label text-heading">
                                        {{__('Pace')}}
                                        <x-info-tooltip text="{{__('Speech speed')}}" />
                                    </label>
                                    <select id="pace" name="pace" class="form-control form-select bg-[#fff] placeholder:text-black">
                                        <option value="x-slow">{{ __('Very Slow') }}</option>
                                        <option value="slow">{{ __('Slow') }}</option>
                                        <option value="medium" selected>{{ __('Medium') }}</option>
                                        <option value="fast">{{ __('Fast') }}</option>
                                        <option value="x-fast">{{ __('Ultra Fast') }}</option>
                                    </select>
                                </div>
                                <div class="grow">
                                    <label for="break" class="form-label text-heading">
                                        {{__('Pause')}}
                                        <x-info-tooltip text="{{__('Wait x seconds after the speech. Represents the time before the next sentence.')}}" />
                                    </label>
                                    <select id="break" name="break" class="form-control form-select bg-[#fff] placeholder:text-black">
                                        <option value="0">{{ __('0s') }}</option>
                                        <option value="1" selected>{{ __('1s') }}</option>
                                        <option value="2">{{ __('2s') }}</option>
                                        <option value="3">{{ __('3s') }}</option>
                                        <option value="4">{{ __('4s') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div id="advanced-settings" class="collapse">
                                <div class="flex flex-wrap justify-between gap-3 mt-8">
                                    <div class="grow">
                                        <label for="image_style" class="form-label text-heading">{{__('Art Style')}}</label>
                                        <select name="image_style" id="image_style" class="form-control form-select bg-[#fff] placeholder:text-black">
                                            <option value="" selected="selected">{{__('None')}}</option>
                                            <option value="3d_render">{{__('3D Render')}}</option>
                                            <option value="anime">{{__('Anime')}}</option>
                                            <option value="ballpoint_pen">{{__('Ballpoint Pen Drawing')}}</option>
                                            <option value="bauhaus">{{__('Bauhaus')}}</option>
                                            <option value="cartoon">{{__('Cartoon')}}</option>
                                            <option value="clay">{{__('Clay')}}</option>
                                            <option value="contemporary">{{__('Contemporary')}}</option>
                                            <option value="cubism">{{__('Cubism')}}</option>
                                            <option value="cyberpunk">{{__('Cyberpunk')}}</option>
                                            <option value="glitchcore">{{__('Glitchcore')}}</option>
                                            <option value="impressionism">{{__('Impressionism')}}</option>
                                            <option value="isometric">{{__('Isometric')}}</option>
                                            <option value="line">{{__('Line Art')}}</option>
                                            <option value="low_poly">{{__('Low Poly')}}</option>
                                            <option value="minimalism">{{__('Minimalism')}}</option>
                                            <option value="modern">{{__('Modern')}}</option>
                                            <option value="origami">{{__('Origami')}}</option>
                                            <option value="pencil">{{__('Pencil Drawing')}}</option>
                                            <option value="pixel">{{__('Pixel')}}</option>
                                            <option value="pointillism">{{__('Pointillism')}}</option>
                                            <option value="pop">{{__('Pop')}}</option>
                                            <option value="realistic">{{__('Realistic')}}</option>
                                            <option value="renaissance">{{__('Renaissance')}}</option>
                                            <option value="retro">{{__('Retro')}}</option>
                                            <option value="steampunk">{{__('Steampunk')}}</option>
                                            <option value="sticker">{{__('Sticker')}}</option>
                                            <option value="ukiyo">{{__('Ukiyo')}}</option>
                                            <option value="vaporwave">{{__('Vaporwave')}}</option>
                                            <option value="vector">{{__('Vector')}}</option>
                                            <option value="watercolor">{{__('Watercolor')}}</option>
                                        </select>
                                    </div>
                                    <div class="grow">
                                        <label for="image_lighting" class="form-label text-heading">{{__('Lightning Style')}}</label>
                                        <select id="image_lighting" name="image_lighting" class="form-control form-select bg-[#fff] placeholder:text-black">
                                            <option value="" selected="selected">{{ __('None') }}</option>
                                            <option value="ambient">{{ __('Ambient') }}</option>
                                            <option value="backlight">{{ __('Backlight') }}</option>
                                            <option value="blue_hour">{{ __('Blue Hour') }}</option>
                                            <option value="cinematic">{{ __('Cinematic') }}</option>
                                            <option value="cold">{{ __('Cold') }}</option>
                                            <option value="dramatic">{{ __('Dramatic') }}</option>
                                            <option value="foggy">{{ __('Foggy') }}</option>
                                            <option value="golden_hour">{{ __('Golden Hour') }}</option>
                                            <option value="hard">{{ __('Hard') }}</option>
                                            <option value="natural">{{ __('Natural') }}</option>
                                            <option value="neon">{{ __('Neon') }}</option>
                                            <option value="studio">{{ __('Studio') }}</option>
                                            <option value="warm">{{ __('Warm') }}</option>
                                        </select>
                                    </div>
                                    <div class="grow">
                                        <label for="image_mood" class="form-label text-heading">{{__('Mood')}}</label>
                                        <select id="image_mood" name="image_mood" class="form-control form-select bg-[#fff] placeholder:text-black">
                                            <option value="" selected="selected">{{ __('None') }}</option>
                                            <option value="aggressive">{{ __('Aggressive') }}</option>
                                            <option value="angry">{{ __('Angry') }}</option>
                                            <option value="boring">{{ __('Boring') }}</option>
                                            <option value="bright">{{ __('Bright') }}</option>
                                            <option value="calm">{{ __('Calm') }}</option>
                                            <option value="cheerful">{{ __('Cheerful') }}</option>
                                            <option value="chilling">{{ __('Chilling') }}</option>
                                            <option value="colorful">{{ __('Colorful') }}</option>
                                            <option value="dark">{{ __('Dark') }}</option>
                                            <option value="neutral">{{ __('Neutral') }}</option>
                                        </select>
                                    </div>
                                    <div class="grow">
                                        <label for="image_number_of_images" class="form-label text-heading">{{__('Number of Images')}}</label>
                                        <select name="image_number_of_images" id="image_number_of_images" class="form-control form-select bg-[#fff] placeholder:text-black">
                                            <option value="1" selected="selected">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="flex items-center space-x-3 mb-2">
                                    <label class="form-label text-heading m-0">{{__('Speeches')}}</label>
                                    <button type="button" class="btn add-new-text text-[12px] bg-[#DAFBEE] rounded-md m-0 py-1 px-2 dark:bg-teal-950">+ {{__('Add New')}}</button>
                                </div>
                                <div class="speeches"></div>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary" id="generate_speech_button">{{__('Generate')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="generator_sidebar_table">
        @include('panel.user.openai.generator_components.generator_sidebar_table')
        </div>
    </div>
@endif
<!-- End image generator -->
