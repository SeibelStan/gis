<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="assets/favicon.png"/>
    <link rel="stylesheet" href="assets/app.css"/>
    <title>2gis Parser</title>
</head>
<body>
    <form action="./gis.csv" class="container" method="post">
        <p><textarea class="text" rows="8" name="url" placeholder="Запрос"></textarea>
        <script>document.querySelector('[name="url"]').value = '<?= $sample ?>'</script>
        <p><input class="text" type="text" name="lim" value="1000" placeholder="Лимит">
	<p>
		<label>Параллельно <input type="radio" name="method"  value="a" checked></label>
		<label>Последовательно <input type="radio" name="method" value="s"></label>
        <p><button class="btn">Скачать</button>
        <footer>
            <abbr title="Найти на 2gis нужное в поиске, на пример, Суши-бары в СПб. В инспекторе Ctrl+Shif+I, вкладке Network найти и скопировать ссылку запроса, похожего на тот, что в textarea выше, вставить в textarea, скачать, открывать Excel или Libre Office Calc, тип разделителя Semicolon (;).">Инструкция</abbr>
        </footer>
        <input type="hidden" name="key" value="<?= $userKey ?>">
    </form>
</body>
</html>