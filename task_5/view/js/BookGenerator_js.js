// ─────────────────────────────── Variables ───────────────────────────────
let currentPage = 1;
const languageEl  = document.getElementById('language');
const seedEl      = document.getElementById('seed');
const likesEl     = document.getElementById('likes');
const reviewsEl   = document.getElementById('reviews');
const exportBtn   = document.getElementById('export-csv');
const randomBtn   = document.getElementById('random-seed');
const likesValue  = document.getElementById('likes-value');
const booksBody   = document.getElementById('books-body');

// ─────────────────────────────── Helpers ───────────────────────────────

// Attach click listeners to any .book-row for toggling its dropdown:
function attachRowClickEvents() {
    document.querySelectorAll('.book-row').forEach(row => {
        row.onclick = function() {
            const next = row.nextElementSibling;
            // If next is already a dropdown, remove it:
            if (next && next.classList.contains('book-details')) {
                next.remove();
                return;
            }
            // Otherwise remove any existing open dropdowns:
            document.querySelectorAll('.book-details').forEach(el => el.remove());

            // Read data attributes:
            const isbn      = row.dataset.isbn;
            const title     = row.dataset.title;
            const author    = row.dataset.author;
            const publisher = row.dataset.publisher;
            const likes     = row.dataset.likes;
            const reviews   = row.dataset.reviews;
            const currentLanguage = languageEl.value;

            // Build the dropdown <tr> structure:
            const tr = document.createElement('tr');
            tr.className = 'book-details';
            tr.innerHTML = `
                  <td colspan="7">
                    <div class="details-content">
                      <img src="https://picsum.photos/seed/${isbn}/200/260" alt="Cover">
                      <div class="info">
                        <h2>${title}</h2>
                        <p><strong>Author:</strong> ${author}</p>
                        <p><strong>Publisher:</strong> ${publisher}</p>
                        <p><strong>Likes:</strong> ${likes}</p>
                        <p><strong>Reviews:</strong> ${reviews}</p>
                        <div><strong>Sample Reviews:</strong></div>
                        ${generateReviewCards(currentLanguage, reviews)}
                      </div>
                    </div>
                  </td>
                `;
            // Insert dropdown right after the clicked row:
            row.parentNode.insertBefore(tr, row.nextSibling);
        };
    });
}

// Generate “count” random review‐cards (purely client‐side sampling):
function generateReviewCards(language, count) {
    const sampleReviews = {
        en: [
            "Absolutely fantastic!",
            "Incredible read!",
            "Could not put it down!",
            "A masterpiece!",
            "Truly exhilarating!",
            "Five stars!",
            "Loved every page!",
            "Brilliantly written!",
            "Unforgettable story!",
            "Highly recommended!",
            "Engaging from start to finish!",
            "A must-read for everyone!",
            "Simply outstanding!",
            "Captivating characters!",
            "Beautifully described!",
            "Heartwarming and epic!",
            "An instant classic!",
            "Spellbinding narrative!",
            "Emotionally powerful!",
            "Masterful storytelling!",
            "Rich, detailed world!",
            "Compelling plot twists!",
            "I want more!",
            "Left me speechless!",
            "Exceeds all expectations!",
            "Took my breath away!",
            "Incredibly immersive!",
            "A literary treasure!",
            "Sophisticated, yet gripping!",
            "Cannot wait for sequel!"
        ],
        de: [
            "Absolut fantastisch!",
            "Unglaublich zu lesen!",
            "Konnte es nicht weglegen!",
            "Ein Meisterwerk!",
            "Wirklich aufregend!",
            "Fünf Sterne!",
            "Jede Seite geliebt!",
            "Brillant geschrieben!",
            "Unvergessliche Geschichte!",
            "Wärmstens empfohlen!",
            "Fesselnd von Anfang bis Ende!",
            "Ein Muss für jeden!",
            "Einfach herausragend!",
            "Fesselnde Charaktere!",
            "Wunderschön beschrieben!",
            "Herzerwärmend und episch!",
            "Ein instant Klassiker!",
            "Bezauberndes Erzählen!",
            "Emotional kraftvoll!",
            "Meisterhafte Erzählkunst!",
            "Reiche, detaillierte Welt!",
            "Spannende Wendungen!",
            "Ich möchte mehr!",
            "Hat mich sprachlos gemacht!",
            "Übertrifft alle Erwartungen!",
            "Hat mir den Atem geraubt!",
            "Unglaublich fesselnd!",
            "Ein literarischer Schatz!",
            "Anspruchsvoll und packend!",
            "Kann den nächsten Teil kaum erwarten!"
        ],
        jp: [
            "素晴らしい！",
            "信じられないほどの読み応え！",
            "ページをめくる手が止まらない！",
            "まさに傑作！",
            "本当にワクワクする物語！",
            "五つ星評価！",
            "すべてのページが好き！",
            "見事に書かれている！",
            "忘れられないストーリー！",
            "心からおすすめ！",
            "最初から最後まで夢中になる！",
            "すべての人に必読！",
            "本当に素晴らしい！",
            "魅力的なキャラクターたち！",
            "美しく描かれた世界！",
            "心温まるエピック！",
            "瞬く間にクラシックに！",
            "物語に引き込まれる！",
            "感動の嵐！",
            "圧倒的なストーリーテリング！",
            "豊かで詳細な世界観！",
            "予想外の展開！",
            "もっと読みたい！",
            "言葉を失うほど感動！",
            "期待を超える作品！",
            "息をのむ展開！",
            "驚くほど没入感！",
            "文学の宝石！",
            "洗練されていて引き込まれる！",
            "続編が待ち遠しい！"
        ]
    };
    // Grab the array for the requested language (fallback to 'en' if not found)
    const pool = sampleReviews[language] || sampleReviews.en;
    let html = "";

    for (let i = 0; i < count; i++) {
        const txt = pool[Math.floor(Math.random() * pool.length)];
        html += `<div class="review-card">${txt}</div>`;
    }
    return html;
}

// Detect if user scrolled near bottom (100px threshold):
function nearBottom() {
    return (window.innerHeight + window.scrollY + 100) >= document.body.scrollHeight;
}

// ─────────────────────────────── AJAX Functions ───────────────────────────────

// 1) Fetch a specific page of rows and append to the table:
function fetchPage(page) {
    const lang    = languageEl.value;
    const seed    = seedEl.value;
    const likes   = likesEl.value;
    const reviews = reviewsEl.value;

    fetch(`?fetch=rows&lang=${lang}&seed=${seed}&likes=${likes}&reviews=${reviews}&page=${page}`)
        .then(res => res.text())
        .then(html => {
            // Append each <tr> from the response into <tbody id="books-body">
            const temp = document.createElement('tbody');
            temp.innerHTML = html.trim();
            Array.from(temp.children).forEach(tr => booksBody.appendChild(tr));

            // Re-attach dropdown listeners to the newly added rows:
            attachRowClickEvents();
        });
}

// 2) Refresh the entire table (header controls changed):
function refreshAll() {
    currentPage = 1;
    booksBody.innerHTML = ''; // Clear existing rows
    fetchPage(1);            // Load page 1 again
}

// 3) Export all currently-loaded rows to CSV (pure JS):
function exportToCSV() {
    // Grab all <tr> inside #books-body (including any dropdown rows, so filter them out)
    const rows = Array.from(booksBody.querySelectorAll('tr.book-row'));
    let csv = 'Index,ISBN,Title,Author,Publisher,Likes,Reviews\n';
    rows.forEach(r => {
        const cells = Array.from(r.querySelectorAll('td'));
        const line = cells.map(td => `"${td.innerText.replace(/"/g,'""')}"`).join(',');
        csv += line + '\n';
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'books.csv';
    a.click();
    URL.revokeObjectURL(url);
}

// ─────────────────────────────── Event Listeners ───────────────────────────────
languageEl.addEventListener('change', refreshAll);
seedEl.addEventListener('change', refreshAll);
randomBtn.addEventListener('click', () => {
    seedEl.value = Math.floor(Math.random() * 1000000);
    refreshAll();
});
likesEl.addEventListener('input', e => {
    likesValue.innerText = e.target.value;
    refreshAll();
});
reviewsEl.addEventListener('change', refreshAll);
exportBtn.addEventListener('click', exportToCSV);

// Infinite scroll: when near bottom, load next page
window.addEventListener('scroll', () => {
    if (nearBottom()) {
        currentPage++;
        fetchPage(currentPage);
    }
});

// Finally, attach row‐click handlers on initial load:
attachRowClickEvents();