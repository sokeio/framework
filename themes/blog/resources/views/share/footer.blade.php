<footer class="site-footer border-top {!! themeOption('footer_color') !!} mt-3">
    <div class="container">
        <div class="py-5">
            <div class="row">
                <div class="col-auto mb-3">
                    <div class="pe-4">{!! themeOption('footer_about') !!}</div>
                </div>
                <div class="col">
                    <div class="row">
                        @for ($i = 1; $i <= 4; $i++)
                            @if ($title = themeOption('footer_column_title' . $i))
                                <div
                                    class="col-6 col-md-2 mb-2 footer-column footer-column-{{ $i }} dropdown-tree">
                                    <h5>{!! $title !!}</h5>
                                    {!! themePosition('footer_column' . $i) !!}
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center pb-2">
            Copyright © {{ date('Y') }} {!! themeOption(
                'footer_copyright',
                '<a href="https://sokeio.com" class=" fw-bold ' .
                    themeOption('footer_color', '') .
                    '" title="Sokeio">Sokeio.com</a>',
            ) !!} All rights reserved. @if (!setting('PLATFORM_HIDE_LOADED_TIME', false))
                Developed by <a href="https://hau.xyz" class="fw-bold {!! themeOption('footer_color') !!}"
                    title="Nguyễn Văn Hậu">Nguyễn Văn Hậu</a>.
                <span class="text-right text-sm ps-1">{{ sokeioTime() }}ms</span>
            @endif
        </div>
    </div>
    </div>
</footer>
