const endpoints = window.cinemaEndpoints ?? { movies: '/api/movies', showtimes: '/api/showtimes' };
const cinemaData = { movies: [], showtimes: [] };

let activeShowtime = null;
let selectedSeatIds = new Set();
let seatsById = new Map();

const movieGrid = document.getElementById('movie-grid');
const movieCount = document.getElementById('movie-count');
const showtimeCount = document.getElementById('showtime-count');
const showtimeList = document.getElementById('showtime-list');
const seatGrid = document.getElementById('seat-grid');
const seatStatus = document.getElementById('seat-status');
const selectedSeats = document.getElementById('selected-seats');
const seatTotal = document.getElementById('seat-total');
const checkout = document.getElementById('checkout');

const gradients = [
    'from-amber-500/35 to-rose-500/10',
    'from-sky-500/30 to-indigo-500/10',
    'from-violet-500/30 to-fuchsia-500/10',
    'from-emerald-400/25 to-cyan-500/10',
    'from-rose-500/30 to-orange-400/10',
];

const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (character) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    "'": '&#39;',
    '"': '&quot;',
})[character]);

const money = (value) => `$${Number(value ?? 0).toFixed(2)}`;

function renderStats() {
    movieCount.textContent = String(cinemaData.movies.length);
    showtimeCount.textContent = String(cinemaData.showtimes.length);
}

function renderMovies() {
    movieGrid.innerHTML = cinemaData.movies.map((movie, index) => {
        const title = movie.title ?? 'Untitled film';
        const duration = movie.duration_minutes ?? movie.duration ?? 0;
        const gradient = gradients[index % gradients.length];

        return `
            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-night-900/60 shadow-xl shadow-black/20 transition duration-300 hover:-translate-y-1 hover:border-gold-400/30 hover:shadow-gold-400/10">
                <div class="relative h-52 overflow-hidden bg-gradient-to-br ${gradient}">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_15%,rgba(255,255,255,0.16),transparent_50%)]"></div>
                    <span class="absolute left-5 top-5 rounded-full border border-white/15 bg-night-950/60 px-3 py-1 text-[11px] font-semibold tracking-wide text-gold-300 backdrop-blur">
                        ${escapeHtml(movie.rating ?? 'PG')}
                    </span>
                    <span class="absolute bottom-3 right-5 text-7xl font-semibold leading-none text-white/10">
                        ${escapeHtml(title.slice(0, 1).toUpperCase())}
                    </span>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-3 text-xs uppercase tracking-widest text-white/40">
                        <span>${escapeHtml(movie.genre ?? 'Drama')}</span>
                        <span class="h-1 w-1 rounded-full bg-gold-400"></span>
                        <span>${escapeHtml(duration)} min</span>
                    </div>
                    <h3 class="mt-3 text-xl font-semibold text-white">${escapeHtml(title)}</h3>
                    <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-white/50">${escapeHtml(movie.description)}</p>
                    <button type="button" data-movie-id="${movie.id}" class="mt-6 flex w-full items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 transition group-hover:border-gold-400/40 group-hover:bg-gold-400/10 group-hover:text-gold-300">
                        Pick a showtime
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </article>
        `;
    }).join('');

    movieGrid.querySelectorAll('[data-movie-id]').forEach((button) => {
        button.addEventListener('click', () => {
            const movieId = Number(button.dataset.movieId);
            const showtime = cinemaData.showtimes.find((item) => item.movieId === movieId);

            if (showtime) {
                selectShowtime(showtime.id, true);
            } else {
                document.getElementById('seats')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

function renderShowtimes() {
    showtimeList.innerHTML = cinemaData.showtimes.length === 0
        ? '<p class="text-sm text-white/40">No showtimes available.</p>'
        : cinemaData.showtimes.map((showtime) => {
            const isActive = showtime.id === activeShowtime?.id;
            const classes = isActive
                ? 'border-gold-400 bg-gold-400/15 text-gold-300 shadow-lg shadow-gold-400/10'
                : 'border-white/10 bg-white/[0.03] text-white/60 hover:border-gold-400/40 hover:text-gold-300';

            return `
                <button type="button" data-showtime-id="${showtime.id}" aria-pressed="${isActive}"
                        class="min-w-max rounded-2xl border px-5 py-4 text-left transition ${classes}">
                    <span class="block text-[11px] font-semibold uppercase tracking-widest text-white/40">${escapeHtml(showtime.startsAt)}</span>
                    <span class="mt-1.5 block text-sm font-semibold">${escapeHtml(showtime.movieTitle)}</span>
                    <span class="mt-1 block text-xs text-white/40">From ${money(showtime.price)}</span>
                </button>
            `;
        }).join('');

    showtimeList.querySelectorAll('[data-showtime-id]').forEach((button) => {
        button.addEventListener('click', () => selectShowtime(Number(button.dataset.showtimeId)));
    });
}

function seatClasses(seat) {
    const base = 'flex h-10 w-10 items-center justify-center rounded-xl border text-xs font-semibold transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold-400';

    if (selectedSeatIds.has(seat.id)) {
        return `${base} border-gold-400 bg-gold-400 text-night-950 shadow-lg shadow-gold-400/30`;
    }

    if (seat.state === 'held') {
        return `${base} cursor-not-allowed border-amber-400/40 bg-amber-500/20 text-amber-200`;
    }

    if (seat.state === 'sold') {
        return `${base} cursor-not-allowed border-white/10 bg-white/5 text-white/25 line-through`;
    }

    return `${base} border-white/20 bg-white/10 text-white/80 hover:border-gold-400 hover:bg-gold-400/20 hover:text-gold-300`;
}

function renderSeats(seats) {
    seatsById = new Map(seats.map((seat) => [seat.id, seat]));

    const rows = seats.reduce((grouped, seat) => {
        (grouped[seat.row] ??= []).push(seat);
        return grouped;
    }, {});

    seatGrid.innerHTML = Object.entries(rows).map(([row, rowSeats]) => {
        const buttons = rowSeats.map((seat) => `
            <button type="button" data-seat-id="${seat.id}" aria-pressed="${selectedSeatIds.has(seat.id)}"
                    aria-label="Row ${escapeHtml(seat.row)} seat ${escapeHtml(seat.number)}, ${escapeHtml(seat.state)}"
                    class="${seatClasses(seat)}">
                ${escapeHtml(seat.number)}
            </button>
        `).join('');

        return `
            <div class="flex items-center justify-center gap-3">
                <span class="w-5 text-right text-xs font-semibold text-white/30">${escapeHtml(row)}</span>
                <div class="flex gap-2">${buttons}</div>
                <span class="w-5 text-xs font-semibold text-white/30">${escapeHtml(row)}</span>
            </div>
        `;
    }).join('');

    seatGrid.querySelectorAll('[data-seat-id]').forEach((button) => {
        button.addEventListener('click', () => toggleSeat(Number(button.dataset.seatId)));
    });
}

function renderSummary() {
    const chosen = [...selectedSeatIds]
        .map((id) => seatsById.get(id))
        .filter((seat) => seat !== undefined);
    const total = chosen.reduce((sum, seat) => sum + Number(seat.price ?? 0), 0);

    selectedSeats.innerHTML = chosen.length === 0
        ? '<p>Tap an available seat to begin.</p>'
        : `<div class="flex flex-wrap gap-2">${chosen.map((seat) => `
            <span class="rounded-lg border border-gold-400/30 bg-gold-400/10 px-3 py-1.5 text-xs font-semibold text-gold-300">
                ${escapeHtml(seat.row)}${escapeHtml(seat.number)}
            </span>
        `).join('')}</div>`;

    seatTotal.textContent = money(total);
    checkout.disabled = chosen.length === 0;
    checkout.textContent = chosen.length === 0
        ? 'Select seats to continue'
        : `Continue · ${money(total)}`;
}

function toggleSeat(seatId) {
    const seat = seatsById.get(seatId);

    if (!seat || seat.state !== 'available') {
        return;
    }

    if (selectedSeatIds.has(seatId)) {
        selectedSeatIds.delete(seatId);
    } else {
        selectedSeatIds.add(seatId);
    }

    renderSeats([...seatsById.values()]);
    renderSummary();
}

async function loadSeatMap(showtime) {
    seatStatus.textContent = `Loading seats for ${showtime.movieTitle}…`;
    seatGrid.innerHTML = '';

    try {
        const response = await fetch(showtime.url);

        if (!response.ok) {
            throw new Error(`Request failed with ${response.status}`);
        }

        const payload = await response.json();
        selectedSeatIds.clear();
        renderSeats(payload.seats);
        renderSummary();
        seatStatus.textContent = `${payload.seats.length} seats · ${showtime.movieTitle}`;
    } catch {
        seatStatus.textContent = 'Unable to load the seat map. Please try again.';
    }
}

function selectShowtime(showtimeId, scrollToSeats = false) {
    const showtime = cinemaData.showtimes.find((item) => item.id === showtimeId);

    if (!showtime) {
        return;
    }

    activeShowtime = showtime;
    selectedSeatIds.clear();
    seatsById.clear();
    renderShowtimes();
    renderSummary();
    loadSeatMap(showtime);

    if (scrollToSeats) {
        document.getElementById('seats')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

async function loadCatalog() {
    try {
        const [moviesResponse, showtimesResponse] = await Promise.all([
            fetch(endpoints.movies),
            fetch(endpoints.showtimes),
        ]);

        if (!moviesResponse.ok || !showtimesResponse.ok) {
            throw new Error('Catalog request failed');
        }

        const [moviesPayload, showtimesPayload] = await Promise.all([
            moviesResponse.json(),
            showtimesResponse.json(),
        ]);

        cinemaData.movies = moviesPayload.data ?? [];
        cinemaData.showtimes = showtimesPayload.showtimes ?? [];
        activeShowtime = cinemaData.showtimes[0] ?? null;

        renderStats();
        renderMovies();
        renderShowtimes();
        renderSummary();

        if (activeShowtime) {
            await loadSeatMap(activeShowtime);
        } else {
            seatStatus.textContent = 'No upcoming showtimes available.';
        }
    } catch {
        seatStatus.textContent = 'Unable to load cinema data. Please try again.';
    }
}

loadCatalog();
