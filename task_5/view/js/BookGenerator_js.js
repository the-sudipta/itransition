/**
 * BookGenerator_js.js
 *
 * Handles:
 *  1. Reading/Reacting to user controls (language, seed, likes, reviews).
 *  2. Infinite scrolling (load page=2,3,…).
 *  3. Toggling the “.book-details” row under each “.book-row”.
 *  4. Exporting visible rows as CSV.
 */

let currentPage = window.initialConfig.page || 1;
let language    = window.initialConfig.lang;
let seedValue   = window.initialConfig.seed;
let likesPer    = parseFloat(window.initialConfig.likes);
let reviewsPer  = parseFloat(window.initialConfig.reviews);
let isFetching  = false; // prevent overlapping fetches

// DOM references
const languageEl = document.getElementById('language');
const seedEl     = document.getElementById('seed');
const likesEl    = document.getElementById('likes');
const likesVal   = document.getElementById('likes-value');
const reviewsEl  = document.getElementById('reviews');
const randomBtn  = document.getElementById('random-seed');
const exportBtn  = document.getElementById('export-csv');
const booksBody  = document.getElementById('books-body');

document.addEventListener('DOMContentLoaded', () => {
    // Initialize control elements
    languageEl.value = language;
    seedEl.value     = seedValue;
    likesEl.value    = likesPer;
    likesVal.innerText = likesPer;
    reviewsEl.value  = reviewsPer;

    // 1) Language change
    languageEl.addEventListener('change', () => {
        language = languageEl.value;
        refreshAll();
    });

    // 2) Seed change
    seedEl.addEventListener('change', () => {
        seedValue = seedEl.value.trim();
        refreshAll();
    });

    // 3) Random Seed button
    randomBtn.addEventListener('click', () => {
        seedValue = Math.floor(Math.random() * 1e9).toString();
        seedEl.value = seedValue;
        refreshAll();
    });

    // 4) Likes slider
    likesEl.addEventListener('input', e => {
        likesPer = parseFloat(e.target.value);
        likesVal.innerText = likesPer;
        refreshAll();
    });

    // 5) Reviews input
    reviewsEl.addEventListener('change', e => {
        reviewsPer = parseFloat(e.target.value);
        refreshAll();
    });

    // 6) Export CSV
    exportBtn.addEventListener('click', exportToCSV);

    // 7) Infinite scroll
    window.addEventListener('scroll', () => {
        if (!isFetching && (window.innerHeight + window.scrollY + 50 >= document.body.offsetHeight)) {
            currentPage++;
            fetchRows(currentPage);
        }
    });

    // 8) Initial load
    fetchRows(1);
});

/**
 * fetchRows(page)
 *
 * Sends a GET request to BookGenerator.php?fetch=rows&…
 * and appends returned <tr>…</tr> elements into #books-body.
 */
function fetchRows(page) {
    isFetching = true;
    const url = `BookGenerator.php?fetch=rows`
        + `&lang=${encodeURIComponent(language)}`
        + `&seed=${encodeURIComponent(seedValue)}`
        + `&likes=${encodeURIComponent(likesPer)}`
        + `&reviews=${encodeURIComponent(reviewsPer)}`
        + `&page=${page}`;

    fetch(url)
        .then(resp => resp.text())
        .then(html => {
            const tempTbody = document.createElement('tbody');
            tempTbody.innerHTML = html.trim();
            Array.from(tempTbody.children).forEach(tr => {
                booksBody.appendChild(tr);
            });
            // Attach click events on newly added .book-row
            attachRowClickEvents();
            isFetching = false;
        })
        .catch(err => {
            console.error('Error fetching rows:', err);
            isFetching = false;
        });
}

/**
 * refreshAll()
 * Clears the table body and reloads page=1.
 */
function refreshAll() {
    currentPage = 1;
    booksBody.innerHTML = '';
    fetchRows(1);
}

/**
 * attachRowClickEvents()
 * Each <tr class="book-row"> toggles the next <tr class="book-details">.
 */
function attachRowClickEvents() {
    document.querySelectorAll('tr.book-row').forEach(row => {
        row.onclick = () => {
            const detailsRow = row.nextElementSibling;
            if (!detailsRow || !detailsRow.classList.contains('book-details')) return;

            // 1) Check if this row’s details are currently visible:
            const wasVisible = detailsRow.style.display === 'table-row';

            // 2) Hide all details rows:
            document.querySelectorAll('tr.book-details').forEach(r => {
                r.style.display = 'none';
            });

            // 3) If it was hidden before, show it; if it was already visible, leave it hidden:
            if (!wasVisible) {
                detailsRow.style.display = 'table-row';
            }
        };
    });
}


/**
 * exportToCSV()
 * Gathers all visible <tr.book-row> and builds a CSV download.
 */
function exportToCSV() {
    const rows = Array.from(booksBody.querySelectorAll('tr.book-row'));
    let csv = 'Index,ISBN,Title,Author,Publisher,Likes,Reviews\n';

    rows.forEach(r => {
        const cells = Array.from(r.querySelectorAll('td'));
        const line  = cells.map((td, idx) => {
            // Grab the raw innerText and escape any embedded quotes
            let text = td.innerText.replace(/"/g, '""');

            // If this is the ISBN column (idx === 1),
            // output exactly ="{digits}" (no outer quotes)
            if (idx === 1) {
                return `="${text}"`;
            }

            // Otherwise wrap everything in normal double quotes
            return `"${text}"`;
        }).join(',');

        csv += line + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'books.csv';
    a.click();
    URL.revokeObjectURL(url);
}


