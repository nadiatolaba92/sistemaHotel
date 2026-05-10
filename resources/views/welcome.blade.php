<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ config('app.name', 'Parque Hotel') }} - Gestión Hotelera</title>

    {{-- Google Fonts: Libre Caslon Text & Manrope --}}
    <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:wght@400;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    {{-- Tailwind CDN con plugins --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed-dim": "#e9c176",
                        "outline": "#737973",
                        "on-surface-variant": "#434843",
                        "on-secondary-fixed-variant": "#5d4201",
                        "tertiary-container": "#35291c",
                        "error-container": "#ffdad6",
                        "primary-container": "#1b3022",
                        "surface-container": "#f0ede8",
                        "on-background": "#1c1c19",
                        "on-secondary": "#ffffff",
                        "on-surface": "#1c1c19",
                        "on-primary-container": "#819986",
                        "surface-container-low": "#f6f3ee",
                        "secondary-container": "#fed488",
                        "error": "#ba1a1a",
                        "surface-container-lowest": "#ffffff",
                        "on-error-container": "#93000a",
                        "background": "#fcf9f4",
                        "primary-fixed-dim": "#b4cdb8",
                        "outline-variant": "#c3c8c1",
                        "tertiary-fixed": "#f4dfcb",
                        "on-primary-fixed": "#0b2013",
                        "inverse-on-surface": "#f3f0eb",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#b4cdb8",
                        "surface": "#fcf9f4",
                        "secondary": "#775a19",
                        "primary": "#061b0e",
                        "surface-variant": "#e5e2dd",
                        "surface-bright": "#fcf9f4",
                        "on-error": "#ffffff",
                        "inverse-surface": "#31302d",
                        "surface-container-highest": "#e5e2dd",
                        "secondary-fixed": "#ffdea5",
                        "on-tertiary-fixed": "#241a0e",
                        "on-tertiary-fixed-variant": "#524436",
                        "on-primary-fixed-variant": "#364c3c",
                        "on-tertiary": "#ffffff",
                        "surface-container-high": "#ebe8e3",
                        "on-secondary-fixed": "#261900",
                        "surface-dim": "#dcdad5",
                        "on-secondary-container": "#785a1a",
                        "tertiary": "#1f1509",
                        "tertiary-fixed-dim": "#d7c3b0",
                        "surface-tint": "#4d6453",
                        "on-tertiary-container": "#a18f7e",
                        "primary-fixed": "#d0e9d4"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "container-max": "1440px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "margin-desktop": "64px",
                        "unit": "8px",
                        "margin-tablet": "32px"
                    },
                    "fontFamily": {
                        "label-md": ["Manrope"],
                        "caption": ["Manrope"],
                        "body-md": ["Manrope"],
                        "headline-md": ["Libre Caslon Text"],
                        "display-lg": ["Libre Caslon Text"],
                        "headline-lg-mobile": ["Libre Caslon Text"],
                        "headline-lg": ["Libre Caslon Text"],
                        "body-lg": ["Manrope"]
                    },
                    "fontSize": {
                        "label-md": ["14px", {
                            "lineHeight": "1.4",
                            "letterSpacing": "0.05em",
                            "fontWeight": "600"
                        }],
                        "caption": ["12px", {
                            "lineHeight": "1.4",
                            "fontWeight": "500"
                        }],
                        "body-md": ["16px", {
                            "lineHeight": "1.5",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "1.4",
                            "fontWeight": "500"
                        }],
                        "display-lg": ["48px", {
                            "lineHeight": "1.2",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "headline-lg-mobile": ["28px", {
                            "lineHeight": "1.3",
                            "fontWeight": "600"
                        }],
                        "headline-lg": ["32px", {
                            "lineHeight": "1.3",
                            "fontWeight": "600"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }]
                    }
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }

        .hero-overlay {
            background: linear-gradient(to bottom, rgba(6, 27, 14, 0.6) 0%, rgba(6, 27, 14, 0.4) 50%, rgba(6, 27, 14, 0.8) 100%);
        }

        .card-shadow {
            box-shadow: 0 10px 40px -10px rgba(27, 48, 34, 0.1);
        }

        .brass-border {
            border-bottom: 1px solid #e9c176;
        }
    </style>
</head>

<body class="bg-background text-on-background font-body-md selection:bg-secondary-fixed-dim selection:text-on-secondary-fixed">

    {{-- ===================== TOP APP BAR ===================== --}}
    <header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-margin-mobile md:px-margin-desktop py-4 bg-background/80 dark:bg-primary/80 backdrop-blur-md border-b border-outline-variant/30">
        <div class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed-dim">
            {{ config('app.name', 'Parque Hotel') }}
        </div>



        <div class="flex gap-4 items-center">
            @auth
            {{-- Usuario autenticado --}}
            <a href="{{ url('admin') }}" class="font-label-md text-label-md text-on-surface-variant px-4 py-2 hover:bg-surface-container-low transition-all rounded-lg active:scale-95 duration-150">
                Dashboard
            </a>
            @else
            {{-- Usuario invitado --}}
            <a href="{{ url('admin/login') }}" class="font-label-md text-label-md bg-primary text-on-primary px-6 py-2.5 rounded-lg hover:shadow-lg transition-all active:scale-95 duration-150">
                Ingresar al Sistema
            </a>
            @endauth
        </div>
    </header>

    <main>
        {{-- ===================== HERO SECTION ===================== --}}
        <section class="relative h-screen flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img
                    alt="Hotel Lobby"
                    class="w-full h-full object-cover"
                    src="https://lh3.googleusercontent.com/aida/ADBb0uhy3citPJ1_SptH6RJhSdX4zbdS1LU-6uQR6zJAaQAydd6rKHkU4p95pQcLKeLRNTli1MgTOULS_i-Vy6lACXs9B2WpnYMhmg7_7jAM1_06FrSId_N_16USEpGhlLiVpfWGSxk0Ht1C5vy_PL6j_EMtnoCf9d1-aXy58kjnetA7D2UlWc3nRprDjwtgHJR8H-b38oavcfu81b1NLYFQ6rb1GDG4-xpVhpq5YqVi5egCOOUAUS1dQW7h4PfJXpWEKtqgJ_ojSq_ZdTo" />
                <div class="absolute inset-0 hero-overlay"></div>
            </div>

            <div class="relative z-10 text-center max-w-4xl px-margin-mobile">
                <h1 class="font-display-lg text-display-lg text-white mb-6">
                    Gestión Hotelera Elevada a la Excelencia
                </h1>
                <p class="font-body-lg text-body-lg text-white/90 mb-10 max-w-2xl mx-auto">
                    Bienvenido al sistema administrativo de Parque Hotel. Optimiza tus operaciones, gestiona reservas y ofrece una experiencia inolvidable a tus huéspedes.
                </p>
                <div class="flex flex-col md:flex-row gap-gutter justify-center items-center">
                    @auth
                    <a href="{{ url('admin') }}" class="w-full md:w-auto bg-primary text-on-primary font-label-md text-label-md px-10 py-4 rounded hover:bg-primary/90 transition-all shadow-xl active:scale-95">
                        Ir al Dashboard
                    </a>
                    @else
                    <a href="{{ url('admin/login') }}" class="w-full md:w-auto bg-primary text-on-primary font-label-md text-label-md px-10 py-4 rounded hover:bg-primary/90 transition-all shadow-xl active:scale-95">
                        Acceder al Sistema
                    </a>
                    @endauth
                </div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
                <span class="material-symbols-outlined text-white/50 text-[32px]">expand_more</span>
            </div>
        </section>



        {{-- ===================== ROOM STATUS PREVIEW ===================== --}}
        <section class="py-24 bg-surface-container-low px-margin-mobile md:px-margin-desktop border-y border-outline-variant/30">
            <div class="max-w-container-max mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-gutter">
                    <div class="max-w-xl">
                        <h2 class="font-headline-lg text-headline-lg text-primary mb-4">Estado de Habitaciones</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Monitoreo instantáneo de la flota de habitaciones. Tome decisiones rápidas basadas en el estado actual de cada unidad.
                        </p>
                    </div>
                    <button class="font-label-md text-label-md border-b border-secondary text-secondary pb-1 flex items-center gap-2 hover:gap-4 transition-all">
                        Ver panel completo <span class="material-symbols-outlined text-sm">open_in_new</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                    {{-- Habitación 101 --}}
                    <div class="bg-surface-container-lowest p-6 rounded shadow-sm border border-outline-variant/20 flex flex-col gap-4">
                        <div class="flex justify-between items-start">
                            <span class="font-headline-md text-headline-md text-primary">101</span>
                            <span class="px-3 py-1 rounded-full text-caption font-caption bg-green-100 text-green-800">Disponible</span>
                        </div>
                        <div class="text-on-surface-variant font-body-md">Suite Presidencial</div>
                        <div class="pt-4 brass-border"></div>
                    </div>

                    {{-- Habitación 102 --}}
                    <div class="bg-surface-container-lowest p-6 rounded shadow-sm border border-outline-variant/20 flex flex-col gap-4">
                        <div class="flex justify-between items-start">
                            <span class="font-headline-md text-headline-md text-primary">102</span>
                            <span class="px-3 py-1 rounded-full text-caption font-caption bg-slate-200 text-slate-700">Ocupada</span>
                        </div>
                        <div class="text-on-surface-variant font-body-md">Habitación Doble</div>
                        <div class="pt-4 brass-border"></div>
                    </div>

                    {{-- Habitación 103 --}}
                    <div class="bg-surface-container-lowest p-6 rounded shadow-sm border border-outline-variant/20 flex flex-col gap-4">
                        <div class="flex justify-between items-start">
                            <span class="font-headline-md text-headline-md text-primary">103</span>
                            <span class="px-3 py-1 rounded-full text-caption font-caption bg-orange-100 text-orange-800">Limpieza</span>
                        </div>
                        <div class="text-on-surface-variant font-body-md">Suite Ejecutiva</div>
                        <div class="pt-4 brass-border"></div>
                    </div>

                    {{-- Habitación 104 --}}
                    <div class="bg-surface-container-lowest p-6 rounded shadow-sm border border-outline-variant/20 flex flex-col gap-4">
                        <div class="flex justify-between items-start">
                            <span class="font-headline-md text-headline-md text-primary">104</span>
                            <span class="px-3 py-1 rounded-full text-caption font-caption bg-green-100 text-green-800">Disponible</span>
                        </div>
                        <div class="text-on-surface-variant font-body-md">Sencilla Deluxe</div>
                        <div class="pt-4 brass-border"></div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===================== CTA FINAL ===================== --}}
        <section class="py-24 text-center px-margin-mobile">
            <div class="max-w-2xl mx-auto">
                <h2 class="font-headline-lg text-headline-lg text-primary mb-6">¿Listo para transformar su gestión?</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant mb-10">Únase a los hoteles que ya optimizan su día a día con nuestra plataforma líder.</p>
                <a href="{{ url('admin/login') }}" class="inline-block bg-primary text-on-primary font-label-md text-label-md px-12 py-4 rounded-lg shadow-2xl hover:scale-105 transition-transform active:scale-95">
                    Solicitar Demo Personalizada
                </a>
            </div>
        </section>
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="w-full py-12 px-margin-mobile md:px-margin-desktop flex flex-col md:flex-row justify-between items-center gap-gutter bg-surface-container-lowest dark:bg-tertiary-container border-t border-outline-variant">
        <div class="flex flex-col items-center md:items-start gap-4">
            <div class="font-headline-md text-headline-md text-primary dark:text-primary-fixed-dim font-bold">
                {{ config('app.name', 'Parque Hotel') }}
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant dark:text-on-tertiary-container/70 max-w-xs text-center md:text-left">
                &copy; {{ date('Y') }} Parque Hotel Management Systems. All rights reserved.
            </p>
        </div>

        <nav class="flex flex-wrap justify-center gap-x-8 gap-y-4">
            <a class="font-body-md text-body-md text-on-surface-variant dark:text-on-tertiary-container/70 hover:text-secondary transition-colors" href="#">Privacy Policy</a>
            <a class="font-body-md text-body-md text-on-surface-variant dark:text-on-tertiary-container/70 hover:text-secondary transition-colors" href="#">Terms of Service</a>
            <a class="font-body-md text-body-md text-on-surface-variant dark:text-on-tertiary-container/70 hover:text-secondary transition-colors" href="#">Contact Support</a>
            <a class="font-body-md text-body-md text-on-surface-variant dark:text-on-tertiary-container/70 hover:text-secondary transition-colors" href="#">Hotel Partners</a>
            <a class="font-body-md text-body-md text-on-surface-variant dark:text-on-tertiary-container/70 hover:text-secondary transition-colors" href="#">Careers</a>
        </nav>

        <div class="flex gap-6">
            <span class="material-symbols-outlined text-primary cursor-pointer hover:text-secondary transition-colors">share</span>
            <span class="material-symbols-outlined text-primary cursor-pointer hover:text-secondary transition-colors">public</span>
            <span class="material-symbols-outlined text-primary cursor-pointer hover:text-secondary transition-colors">mail</span>
        </div>
    </footer>

</body>

</html>