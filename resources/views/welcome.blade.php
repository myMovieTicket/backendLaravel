<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#06060b">
        <title>Lumière Cinema — Your next movie night</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-night-950 font-sans text-white selection:bg-gold-400 selection:text-night-950">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -left-40 -top-40 h-[36rem] w-[36rem] rounded-full bg-gold-500/10 blur-[140px]"></div>
            <div class="absolute -right-40 top-1/3 h-[32rem] w-[32rem] rounded-full bg-ember-400/10 blur-[150px]"></div>
            <div class="absolute bottom-0 left-1/3 h-[28rem] w-[28rem] rounded-full bg-indigo-500/10 blur-[160px]"></div>
        </div>

        <header class="sticky top-0 z-50 border-b border-white/5 bg-night-950/80 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-5">
                <a href="/" class="flex items-center gap-3 text-lg font-semibold tracking-tight">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-gold-400 text-night-950 shadow-lg shadow-gold-400/20">L</span>
                    <span>Lumière <span class="text-white/40">Cinema</span></span>
                </a>
                <div class="hidden items-center gap-8 text-sm text-white/60 md:flex">
                    <a href="#movies" class="transition hover:text-gold-300">Now showing</a>
                    <a href="#seats" class="transition hover:text-gold-300">Choose seats</a>
                    <a href="#experience" class="transition hover:text-gold-300">Experience</a>
                </div>
                <a href="#seats" class="rounded-full bg-gold-400 px-5 py-2.5 text-sm font-semibold text-night-950 shadow-lg shadow-gold-400/20 transition hover:bg-gold-300">
                    Book tickets
                </a>
            </nav>
        </header>

        <main>
            <section class="relative px-6 pb-28 pt-20 lg:pt-28">
                <div class="mx-auto grid max-w-7xl items-center gap-16 lg:grid-cols-[1.05fr_.95fr]">
                    <div>
                        <p class="mb-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.35em] text-gold-400">
                            <span class="h-px w-10 bg-gold-400/60"></span>
                            The big screen awaits
                        </p>
                        <h1 class="max-w-3xl text-5xl font-semibold leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
                            Movie nights,
                            <span class="block bg-gradient-to-r from-gold-300 via-gold-400 to-ember-400 bg-clip-text text-transparent">made memorable.</span>
                        </h1>
                        <p class="mt-7 max-w-xl text-lg leading-relaxed text-white/60">
                            Discover what is playing, pick your perfect seat, and settle in for an evening of stories worth staying for.
                        </p>
                        <div class="mt-10 flex flex-wrap items-center gap-4">
                            <a href="#movies" class="rounded-full bg-gold-400 px-7 py-3.5 text-sm font-semibold text-night-950 shadow-xl shadow-gold-400/25 transition hover:-translate-y-0.5 hover:bg-gold-300">
                                Explore films
                            </a>
                            <a href="#seats" class="rounded-full border border-white/15 px-7 py-3.5 text-sm font-semibold text-white/80 transition hover:border-gold-400/60 hover:text-gold-300">
                                View seat map
                            </a>
                        </div>
                        <dl class="mt-14 grid max-w-lg grid-cols-3 gap-6 border-t border-white/10 pt-8">
                            <div>
                                <dt class="text-xs uppercase tracking-widest text-white/40">Films</dt>
                                <dd id="movie-count" class="mt-2 text-2xl font-semibold">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-widest text-white/40">Showtimes</dt>
                                <dd id="showtime-count" class="mt-2 text-2xl font-semibold">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-widest text-white/40">Seats</dt>
                                <dd class="mt-2 text-2xl font-semibold">40</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative">
                        <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-gold-400/20 via-transparent to-ember-400/20 blur-2xl"></div>
                        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-night-900 shadow-2xl shadow-black/60">
                            <div class="relative h-[26rem] bg-gradient-to-br from-amber-500/40 via-night-800 to-rose-500/25 sm:h-[32rem]">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(255,255,255,0.22),transparent_45%)]"></div>
                                <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-night-950 via-night-950/70 to-transparent"></div>
                                <div class="absolute left-8 top-8 flex items-center gap-2 rounded-full border border-white/15 bg-night-950/60 px-4 py-2 text-xs font-medium text-gold-300 backdrop-blur">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gold-400"></span>
                                    Featured tonight
                                </div>
                                <div class="absolute bottom-8 left-8 right-8">
                                    <p class="text-xs uppercase tracking-[0.3em] text-gold-400">Sci-Fi · PG-13</p>
                                    <h2 class="mt-3 text-4xl font-semibold tracking-tight">Inception</h2>
                                    <p class="mt-3 max-w-md text-sm leading-relaxed text-white/60">
                                        A thief who steals corporate secrets through dream-sharing technology is offered a chance at redemption.
                                    </p>
                                    <div class="mt-6 flex items-center gap-4 text-sm text-white/70">
                                        <span class="rounded-full border border-white/15 px-3 py-1.5">120 min</span>
                                        <span class="rounded-full border border-white/15 px-3 py-1.5">Screen 1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-6 -left-6 hidden rounded-2xl border border-white/10 bg-night-800/90 p-5 shadow-2xl backdrop-blur sm:block">
                            <p class="text-xs uppercase tracking-widest text-white/40">Tonight</p>
                            <p class="mt-1 text-lg font-semibold text-gold-300">7:00 PM</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="movies" class="px-6 py-24">
                <div class="mx-auto max-w-7xl">
                    <div class="flex flex-wrap items-end justify-between gap-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold-400">Now showing</p>
                            <h2 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">Stories worth the trip</h2>
                        </div>
                        <p class="max-w-md text-sm leading-relaxed text-white/50">
                            Five hand-picked films, three days of showtimes, and a seat map that makes choosing your spot effortless.
                        </p>
                    </div>

                    <div id="movie-grid" class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3"></div>
                </div>
            </section>

            <section id="seats" class="px-6 py-24">
                <div class="mx-auto max-w-7xl">
                    <div class="flex flex-wrap items-end justify-between gap-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold-400">Pick your spot</p>
                            <h2 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">Choose a showtime</h2>
                        </div>
                        <p class="max-w-md text-sm leading-relaxed text-white/50">
                            Gold seats are available, amber seats are on hold, and faded seats are already sold.
                        </p>
                    </div>

                    <div id="showtime-list" class="mt-10 flex gap-3 overflow-x-auto pb-2"></div>

                    <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
                        <div class="rounded-3xl border border-white/10 bg-night-900/70 p-6 shadow-2xl shadow-black/30 backdrop-blur sm:p-8">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/40">Screen 1</p>
                                    <p id="seat-status" class="mt-1 text-sm font-medium text-gold-300" aria-live="polite">Preparing seat map…</p>
                                </div>
                                <div class="flex flex-wrap gap-4 text-xs text-white/50">
                                    <span class="flex items-center gap-2"><span class="h-3 w-3 rounded border border-white/20 bg-white/10"></span>Available</span>
                                    <span class="flex items-center gap-2"><span class="h-3 w-3 rounded border border-amber-400/40 bg-amber-500/25"></span>Held</span>
                                    <span class="flex items-center gap-2"><span class="h-3 w-3 rounded border border-white/10 bg-white/5"></span>Sold</span>
                                </div>
                            </div>

                            <div class="overflow-x-auto py-10">
                                <div class="mx-auto w-max">
                                    <div class="mx-auto h-3 w-4/5 rounded-[50%] bg-gradient-to-r from-transparent via-gold-400/70 to-transparent blur-[2px]"></div>
                                    <p class="mt-3 text-center text-[10px] font-semibold tracking-[0.5em] text-white/30">SCREEN</p>
                                    <div id="seat-grid" class="mt-10 space-y-3"></div>
                                </div>
                            </div>
                        </div>

                        <aside class="h-fit rounded-3xl border border-white/10 bg-night-900/80 p-6 shadow-2xl shadow-black/30 backdrop-blur lg:sticky lg:top-28">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/40">Your selection</p>
                            <h3 class="mt-2 text-2xl font-semibold">Make it yours</h3>
                            <div id="selected-seats" class="mt-6 min-h-24 rounded-2xl border border-dashed border-white/10 bg-white/[0.02] p-4 text-sm text-white/40" aria-live="polite">
                                Tap an available seat to begin.
                            </div>
                            <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-5">
                                <span class="text-sm text-white/50">Total</span>
                                <span id="seat-total" class="text-2xl font-semibold text-gold-300">$0.00</span>
                            </div>
                            <button id="checkout" type="button" disabled class="mt-6 w-full rounded-xl bg-gold-400 px-5 py-3.5 text-sm font-semibold text-night-950 transition hover:bg-gold-300 disabled:cursor-not-allowed disabled:opacity-40">
                                Select seats to continue
                            </button>
                            <p class="mt-4 text-center text-xs text-white/35">Demo checkout — booking flow coming soon.</p>
                        </aside>
                    </div>
                </div>
            </section>

            <section id="experience" class="px-6 py-24">
                <div class="mx-auto max-w-7xl">
                    <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-gold-400/15 via-night-900 to-ember-400/10 p-10 sm:p-16">
                        <div class="grid gap-10 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold-400">The experience</p>
                                <h2 class="mt-4 max-w-2xl text-4xl font-semibold leading-tight tracking-tight sm:text-5xl">
                                    Every seat has a story. Pick the one you want to be in.
                                </h2>
                                <p class="mt-5 max-w-xl text-sm leading-relaxed text-white/60">
                                    Live availability, clear pricing, and a seat map built to make the choice feel as good as the movie.
                                </p>
                            </div>
                            <a href="#seats" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-night-950 transition hover:bg-gold-300">
                                Find your seat <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/5 px-6 py-10">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 text-sm text-white/40">
                <span>© {{ date('Y') }} Lumière Cinema</span>
                <span>Lights down. Curtain up.</span>
            </div>
        </footer>

        <script>
            window.cinemaEndpoints = @json(['movies' => route('movies.index'), 'showtimes' => route('showtimes.index')]);
        </script>
    </body>
</html>
