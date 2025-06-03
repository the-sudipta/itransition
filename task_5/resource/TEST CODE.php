<?php
// PHP variables for default values
$defaultSeed = isset($_GET['seed']) ? $_GET['seed'] : '42';
$defaultLanguage = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$defaultLikes = isset($_GET['likes']) ? $_GET['likes'] : '3.7';
$defaultReviews = isset($_GET['reviews']) ? $_GET['reviews'] : '4.7';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookstore Test App</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/seedrandom/3.0.5/seedrandom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0A0A23, #1B1B2F);
            color: #FFD700;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #111;
            padding: 1rem;
            text-align: center;
            border-bottom: 2px solid #FFD700;
        }
        main {
            padding: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            background-color: #1B1B2F;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        label {
            margin-right: 0.5rem;
        }
        input[type='text'],
        select,
        input[type='number'],
        input[type='range'] {
            background-color: #222;
            color: #FFD700;
            border: 1px solid #FFD700;
            border-radius: 4px;
            padding: 0.25rem;
        }
        button {
            background-color: #FFD700;
            color: #0A0A23;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e6c200;
        }
        #books-table {
            background-color: #111;
            border: 1px solid #FFD700;
            border-radius: 8px;
            padding: 1rem;
        }
        .book-row {
            border-bottom: 1px solid #FFD700;
            padding: 0.5rem 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .book-row:hover {
            background-color: #1B1B2F;
        }
        .book-details {
            background-color: #222;
            color: #EEE;
            padding: 1rem;
            margin-top: 0.5rem;
            border-radius: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            border: 1px solid #FFD700;
        }
        .book-details img {
            width: 150px;
            height: 220px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #FFD700;
        }
        .book-info {
            flex: 1;
            min-width: 200px;
        }
        .book-info h2 {
            margin: 0 0 0.5rem 0;
            color: #FFD700;
        }
        .book-info p {
            margin: 0.3rem 0;
        }
        .book-reviews {
            margin-top: 0.5rem;
        }
        .review-card {
            background-color: #1B1B2F;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.3);
        }
    </style>
</head>
<body>
<header>
    <h1>📚 Bookstore Testing App</h1>
</header>
<main>
    <section class="controls">
        <label for="language">Language & Region:</label>
        <select id="language">
            <option value="en" <?php if ($defaultLanguage == 'en') echo 'selected'; ?>>English (USA)</option>
            <option value="de" <?php if ($defaultLanguage == 'de') echo 'selected'; ?>>German (Germany)</option>
            <option value="jp" <?php if ($defaultLanguage == 'jp') echo 'selected'; ?>>Japanese (Japan)</option>
        </select>
        <label for="seed">Seed:</label>
        <input type="text" id="seed" placeholder="Enter seed" value="<?php echo htmlspecialchars($defaultSeed); ?>">
        <button id="random-seed">🔀 Random Seed</button>
        <label for="likes">Likes per Book:</label>
        <input type="range" id="likes" min="0" max="10" step="0.1" value="<?php echo htmlspecialchars($defaultLikes); ?>">
        <span id="likes-value"><?php echo htmlspecialchars($defaultLikes); ?></span>
        <label for="reviews">Reviews per Book:</label>
        <input type="number" id="reviews" min="0" max="10" step="0.1" value="<?php echo htmlspecialchars($defaultReviews); ?>">
        <button id="export-csv">Export CSV</button>
    </section>
    <section id="books-table"></section>
</main>

<script>
    let currentPage = 1;
    let seedValue = '<?php echo htmlspecialchars($defaultSeed); ?>';
    let language = '<?php echo htmlspecialchars($defaultLanguage); ?>';
    let likesPerBook = parseFloat('<?php echo htmlspecialchars($defaultLikes); ?>');
    let reviewsPerBook = parseFloat('<?php echo htmlspecialchars($defaultReviews); ?>');
    let booksData = [];

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('language').value = language;
        document.getElementById('language').addEventListener('change', (e) => {
            language = e.target.value;
            currentPage = 1;
            booksData = [];
            renderBooks();
        });
        document.getElementById('seed').addEventListener('change', (e) => {
            seedValue = e.target.value;
            currentPage = 1;
            booksData = [];
            renderBooks();
        });
        document.getElementById('random-seed').addEventListener('click', () => {
            seedValue = Math.floor(Math.random() * 1000000).toString();
            document.getElementById('seed').value = seedValue;
            currentPage = 1;
            booksData = [];
            renderBooks();
        });
        document.getElementById('likes').addEventListener('input', (e) => {
            likesPerBook = parseFloat(e.target.value);
            document.getElementById('likes-value').innerText = likesPerBook;
            renderBooks();
        });
        document.getElementById('reviews').addEventListener('change', (e) => {
            reviewsPerBook = parseFloat(e.target.value);
            renderBooks();
        });
        document.getElementById('export-csv').addEventListener('click', exportToCSV);
        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                currentPage++;
                renderBooks();
            }
        });
        renderBooks();
    });

    function seededRandom(seed) {
        const rng = new Math.seedrandom(seed);
        return () => rng();
    }

    function renderBooks() {
        const container = document.getElementById('books-table');
        if (currentPage === 1) {
            container.innerHTML = '';
        }
        const random = seededRandom(seedValue + currentPage);
        for (let i = 1; i <= (currentPage === 1 ? 20 : 10); i++) {
            const index = booksData.length + 1;
            const isbn = Math.floor(random() * 1000000000000);
            const title = generateTitle(language, random);
            const author = generateAuthor(language, random);
            const publisher = generatePublisher(language, random);
            const likes = getRandomInt(likesPerBook, random);
            const reviews = getRandomInt(reviewsPerBook, random);
            const row = document.createElement('div');
            row.className = 'book-row';
            row.innerHTML = `<strong>#${index}</strong> | ISBN: ${isbn} | Title: ${title} | Author(s): ${author} | Publisher: ${publisher} | Likes: ${likes} | Reviews: ${reviews}`;

            row.addEventListener('click', () => {
                const existingDetails = row.querySelector('.book-details');
                if (existingDetails) {
                    existingDetails.remove();
                } else {
                    const details = document.createElement('div');
                    details.className = 'book-details';
                    const imageUrl = `https://picsum.photos/seed/${isbn}/150/220`;
                    details.innerHTML = `
                        <img src="${imageUrl}" alt="Book Cover">
                        <div class="book-info">
                            <h2>${title}</h2>
                            <p><strong>Serial:</strong> #${index}</p>
                            <p><strong>ISBN:</strong> ${isbn}</p>
                            <p><strong>Author:</strong> ${author}</p>
                            <p><strong>Publisher:</strong> ${publisher}</p>
                            <p><strong>Likes:</strong> ${likes}</p>
                            <div class="book-reviews">
                                <strong>Reviews:</strong>
                                ${generateReviewCards(language, reviews, random)}
                            </div>
                        </div>`;
                    row.appendChild(details);
                }
            });
            container.appendChild(row);
            booksData.push({index, isbn, title, author, publisher, likes, reviews});
        }
    }

    function generateTitle(lang, random) {
        const titles = {
            en: ['The Lost City', 'Shadows and Light', 'Infinite Dreams'],
            de: ['Die verlorene Stadt', 'Schatten und Licht', 'Unendliche Träume'],
            jp: ['失われた街', '影と光', '無限の夢']
        };
        const list = titles[lang] || titles.en;
        return list[Math.floor(random() * list.length)];
    }

    function generateAuthor(lang, random) {
        const authors = {
            en: ['John Smith', 'Jane Doe', 'Alice Johnson'],
            de: ['Hans Müller', 'Anna Schmidt', 'Peter Fischer'],
            jp: ['山田太郎', '佐藤花子', '鈴木一郎']
        };
        const list = authors[lang] || authors.en;
        return list[Math.floor(random() * list.length)];
    }

    function generatePublisher(lang, random) {
        const publishers = {
            en: ['HarperCollins', 'Penguin Random House', 'Simon & Schuster'],
            de: ['Verlag Müller', 'Buchverlag Schmidt', 'Fischer Bücher'],
            jp: ['講談社', '集英社', '角川書店']
        };
        const list = publishers[lang] || publishers.en;
        return list[Math.floor(random() * list.length)];
    }

    function getRandomInt(value, random) {
        const intPart = Math.floor(value);
        const fractional = value - intPart;
        let result = intPart;
        if (random() < fractional) result++;
        return result;
    }

    function generateReviewCards(lang, count, random) {
        const sampleReviews = {
            en: ['Great book!', 'Loved it!', 'Could not put it down!'],
            de: ['Tolles Buch!', 'Hat mir gefallen!', 'Konnte es nicht aus der Hand legen!'],
            jp: ['素晴らしい本！', 'とても良かった！', '止まらなかった！']
        };
        const list = sampleReviews[lang] || sampleReviews.en;
        let reviews = '';
        for (let i = 0; i < count; i++) {
            reviews += `<div class="review-card">${list[Math.floor(random() * list.length)]}</div>`;
        }
        return reviews;
    }

    function exportToCSV() {
        const csv = Papa.unparse(booksData);
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'books.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

</body>
</html>
