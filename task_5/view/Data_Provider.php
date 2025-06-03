<?php
ob_start();
try{

    // No need for Session as there is no Login system
    global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;
    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/routes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/view/Data_Provider.php';


    // A helper to turn a fractional like/review into an integer (+ probabilistic):
    function getRandomInt($value) {
        $intPart    = floor($value);
        $fractional = $value - $intPart;
        return $intPart + ((mt_rand() / mt_getrandmax()) < $fractional ? 1 : 0);
    }

    // Generate a batch of $count “books,” seeded by ($seed + $page) so each page is stable.
    function generateBooks($seed, $lang, $likesPer, $reviewsPer, $page, $count = 20) {
        // Combine seed + page for consistent RNG across pages:
        mt_srand(crc32($seed . '_' . $page));

        // Lookup tables for titles/authors/publishers by language:
        $titlesList = [
            'en' => [
                'The Lost City',
                'Shadows and Light',
                'Infinite Dreams',
                'Beyond the Horizon',
                'Midnight Tales',
                'Hidden Truths',
                'Echoes of Time',
                'Crimson Skies',
                'Forgotten Legends',
                'Whispering Winds',
                'Whispering Shadows',
                'Celestial Paths',
                'Eternal Dawn',
                'Broken Reflections',
                'Rising Storm',
                'Silent Echoes',
                'Fractured Souls',
                'Hidden Realms',
                'Distant Memories',
                'Veil of Secrets',
                'Burning Empires',
                'Fading Stars',
                'Crystal Abyss',
                'Midnight Reverie',
                'Awakened Destiny',
                'Veins of Gold',
                'Veiled Horizons',
                'Twilight Requiem',
                'Veil of Time',
                'Forgotten Promise'
            ],
            'de' => [
                'Die verlorene Stadt',
                'Schatten und Licht',
                'Unendliche Träume',
                'Jenseits des Horizonts',
                'Mitternachtsgeschichten',
                'Verborgene Wahrheiten',
                'Echos der Zeit',
                'Purpurne Himmel',
                'Vergessene Legenden',
                'Flüsternde Winde',
                'Flüsternde Schatten',
                'Himmlische Pfade',
                'Ewige Dämmerung',
                'Zerbrochene Spiegel',
                'Aufziehender Sturm',
                'Stille Echos',
                'Zerbrochene Seelen',
                'Verborgene Reiche',
                'Ferne Erinnerungen',
                'Schleier der Geheimnisse',
                'Brennende Imperien',
                'Verblassende Sterne',
                'Kristallabgrund',
                'Mitternachtstraum',
                'Erwecktes Schicksal',
                'Adern aus Gold',
                'Verhüllte Horizonte',
                'Zwielicht-Requiem',
                'Der Schleier der Zeit',
                'Vergessenes Versprechen'
            ],
            'jp' => [
                '失われた街',
                '影と光',
                '無限の夢',
                '地平線の向こう',
                '真夜中の物語',
                '隠された真実',
                '時のこだま',
                '赤い空',
                '忘れられた伝説',
                'ささやく風',
                'ささやく影',
                '天の道',
                '永遠の夜明け',
                '砕けた鏡',
                '嵐の予感',
                '静寂のこだま',
                '壊れた魂',
                '隠れた王国',
                '遠い記憶',
                '秘密のヴェール',
                '燃える帝国',
                '消えゆく星',
                '水晶の深淵',
                '真夜中の幻想',
                '目覚めた運命',
                '黄金の血脈',
                '隠された地平線',
                '黄昏の鎮魂歌',
                '時のヴェール',
                '忘れられた誓い'
            ],
        ];

        $authorsList = [
            'en' => [
                'John Smith',
                'Jane Doe',
                'Alice Johnson',
                'Michael Brown',
                'Emily Davis',
                'Robert Wilson',
                'Olivia Martinez',
                'David Taylor',
                'Sophia Anderson',
                'James Thomas',
                'Isabella Moore',
                'Christopher Lee',
                'Mia Thompson',
                'Daniel Harris',
                'Charlotte Clark',
                'Matthew Lewis',
                'Amelia Walker',
                'Anthony Hall',
                'Abigail Young',
                'Joshua King',
                'Emma Wright',
                'Andrew Scott',
                'Ava Green',
                'Joseph Adams',
                'Harper Baker',
                'William Nelson',
                'Ella Carter',
                'Benjamin Mitchell',
                'Lily Roberts',
                'Samuel Turner'
            ],
            'de' => [
                'Hans Müller',
                'Anna Schmidt',
                'Peter Fischer',
                'Laura Wagner',
                'Karl Becker',
                'Monika Schmitz',
                'Thomas Berg',
                'Julia Braun',
                'Oliver Koch',
                'Sandra Hofmann',
                'Maximilian Meyer',
                'Lena Klein',
                'Felix Schwarz',
                'Emma Hofmann',
                'Jonas Neumann',
                'Lea Lehmann',
                'Paul Schuster',
                'Mia Vogel',
                'Lukas Richter',
                'Sophie Weiß',
                'Tim Schäfer',
                'Clara König',
                'Jan Huber',
                'Nina Scholz',
                'Lukas Zimmermann',
                'Laura Herzog',
                'Simon Graf',
                'Mara Beck',
                'Erik Fuchs',
                'Nora Baum'
            ],
            'jp' => [
                '山田太郎',
                '佐藤花子',
                '鈴木一郎',
                '高橋美咲',
                '田中優太',
                '伊藤玲奈',
                '渡辺健太',
                '佐々木茜',
                '山本悠斗',
                '小林真央',
                '加藤翔太',
                '中村愛',
                '吉田誠',
                '斎藤由美',
                '山口拓海',
                '松本真子',
                '井上大輔',
                '木村彩',
                '林航太',
                '清水菜々',
                '池田健',
                '石井葵',
                '内田涼',
                '橋本結衣',
                '山崎涼太',
                '藤田美咲',
                '小川悠真',
                '中島結',
                '後藤健吾',
                '野口真帆'
            ],
        ];

        $publishersList = [
            'en' => [
                'HarperCollins',
                'Penguin Random House',
                'Simon & Schuster',
                'Hachette Book Group',
                'Macmillan Publishers',
                'Random House',
                'Scholastic',
                'Bloomsbury Publishing',
                'Oxford University Press',
                'Wiley',
                'Pearson Education',
                'Cengage Learning',
                'Springer',
                'Cambridge University Press',
                'McGraw-Hill',
                'Farrar Straus Giroux',
                'St. Martin’s Press',
                'Harlequin',
                'Tor Books',
                'Chronicle Books',
                'Workman Publishing',
                'Puffin Books',
                'Knopf Doubleday',
                'Bloomsbury USA',
                'Quirk Books',
                'Sourcebooks',
                'Chronicle Prism',
                'Hay House',
                'Zakariya Publishing',
                'Forest Hill Press'
            ],
            'de' => [
                'Verlag Müller',
                'Buchverlag Schmidt',
                'Fischer Bücher',
                'Droemer Knaur',
                'Heyne Verlag',
                'Rowohlt Verlag',
                'Carlsen Verlag',
                'Suhrkamp Verlag',
                'Carl Hanser Verlag',
                'S. Fischer Verlag',
                'Ullstein Verlag',
                'Piper Verlag',
                'Kiepenheuer & Witsch',
                'dtv (Deutscher Taschenbuch Verlag)',
                'Random House Germany',
                'Egmont Schneiderbuch',
                'HarperCollins Germany',
                'Bastei Lübbe',
                'Goldmann Verlag',
                'Campus Verlag',
                'Cornelsen Verlag',
                'Gräfe und Unzer',
                'Marix Verlag',
                'Beltz Verlag',
                'Nikol Verlagsgesellschaft',
                'FISCHER Tor',
                'Schott Music',
                'Gütersloher Verlagshaus',
                'Klett-Cotta',
                '19c Verlag'
            ],
            'jp' => [
                '講談社',
                '集英社',
                '角川書店',
                '新潮社',
                '文藝春秋',
                '宝島社',
                '小学館',
                '文響社',
                'ポプラ社',
                'KADOKAWA',
                '講談社ブルース',
                '幻冬舎',
                '光文社',
                '徳間書店',
                '朝日新聞出版',
                '祥伝社',
                '幻冬舎新書',
                '新潮文庫',
                '小学館幻戯書房',
                'PHP研究所',
                '女子パウロ会',
                'ほるぷ出版',
                '岩波書店',
                '筑摩書房',
                '講談社現代新書',
                '講談社BOX',
                'ちくま文庫',
                '白水社',
                '青土社',
                '中公文庫'
            ],
        ];

        $books = [];
        for ($i = 1; $i <= $count; $i++) {
            $globalIndex = ($page - 1) * $count + $i; // e.g. page 2 → indices 21–40
            $isbn = mt_rand(100000000000, 999999999999);
            $title = $titlesList[$lang][array_rand($titlesList[$lang])];
            $author = $authorsList[$lang][array_rand($authorsList[$lang])];
            $publisher = $publishersList[$lang][array_rand($publishersList[$lang])];
            $likes   = getRandomInt((float)$likesPer);
            $reviews = getRandomInt((float)$reviewsPer);

            $books[] = [
                'index'    => $globalIndex,
                'isbn'     => $isbn,
                'title'    => $title,
                'author'   => $author,
                'publisher'=> $publisher,
                'likes'    => $likes,
                'reviews'  => $reviews
            ];
        }
        return $books;
    }

    // Render a page’s worth of <tr>…</tr> based on generateBooks():
    function renderRows($books) {
        foreach ($books as $book) {
            // Each row gets data- attributes so JS can pop open the dropdown:
            echo "<tr class='book-row'
                      data-index='{$book['index']}'
                      data-isbn='{$book['isbn']}'
                      data-title='{$book['title']}'
                      data-author='{$book['author']}'
                      data-publisher='{$book['publisher']}'
                      data-likes='{$book['likes']}'
                      data-reviews='{$book['reviews']}'
                  >
                  <td>{$book['index']}</td>
                  <td>{$book['isbn']}</td>
                  <td>{$book['title']}</td>
                  <td>{$book['author']}</td>
                  <td>{$book['publisher']}</td>
                  <td>{$book['likes']}</td>
                  <td>{$book['reviews']}</td>
                </tr>";
        }
    }



} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = " View -> Data_Provider File";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();
